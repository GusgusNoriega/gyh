<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SmtpSetting;
use App\Models\EmailTemplate;
use App\Services\EmailTemplateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

/**
 * Tests para el Email Service Package
 * 
 * Copiar a tests/Feature/ y ejecutar con:
 * php artisan test --filter=EmailServiceTest
 */
class EmailServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Fake emails para no enviar realmente
        Mail::fake();
    }

    /** @test */
    public function smtp_settings_can_be_retrieved()
    {
        // Crear configuración de prueba
        SmtpSetting::create([
            'key' => 'smtp_host',
            'value' => 'smtp.test.com',
            'description' => 'Test SMTP Host'
        ]);

        $value = SmtpSetting::getValue('smtp_host');
        
        $this->assertEquals('smtp.test.com', $value);
    }

    /** @test */
    public function smtp_settings_can_be_set()
    {
        SmtpSetting::setValue('smtp_host', 'smtp.new.com', 'New SMTP Host');
        
        $setting = SmtpSetting::where('key', 'smtp_host')->first();
        
        $this->assertEquals('smtp.new.com', $setting->value);
        $this->assertEquals('New SMTP Host', $setting->description);
    }

    /** @test */
    public function smtp_config_returns_complete_array()
    {
        SmtpSetting::setValue('smtp_host', 'smtp.test.com');
        SmtpSetting::setValue('smtp_port', '587');
        SmtpSetting::setValue('smtp_username', 'user@test.com');
        
        $config = SmtpSetting::smtpConfig();
        
        $this->assertArrayHasKey('host', $config);
        $this->assertArrayHasKey('port', $config);
        $this->assertArrayHasKey('encryption', $config);
        $this->assertArrayHasKey('username', $config);
        $this->assertArrayHasKey('password', $config);
        $this->assertArrayHasKey('from_address', $config);
        $this->assertArrayHasKey('from_name', $config);
        
        $this->assertEquals('smtp.test.com', $config['host']);
        $this->assertEquals(587, $config['port']);
    }

    /** @test */
    public function email_template_can_be_created()
    {
        $template = EmailTemplate::create([
            'name' => 'Test Template',
            'key' => 'test_template',
            'subject' => 'Test Subject [name]',
            'content_html' => '<h1>Hello [name]</h1>',
            'variables_schema' => [
                'name' => ['required' => true, 'description' => 'User name']
            ],
            'is_active' => true
        ]);
        
        $this->assertDatabaseHas('email_templates', [
            'key' => 'test_template',
            'is_active' => true
        ]);
    }

    /** @test */
    public function email_template_can_be_soft_deleted()
    {
        $template = EmailTemplate::create([
            'name' => 'Test Template',
            'key' => 'soft_delete_test',
            'subject' => 'Test',
            'content_html' => '<p>Test</p>',
            'is_active' => true
        ]);
        
        $template->delete();
        
        $this->assertSoftDeleted('email_templates', [
            'key' => 'soft_delete_test'
        ]);
        
        // Puede ser recuperado
        $template->restore();
        
        $this->assertDatabaseHas('email_templates', [
            'key' => 'soft_delete_test',
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function email_service_throws_exception_for_missing_template()
    {
        $service = new EmailTemplateService();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('La plantilla de correo [nonexistent] no existe o está inactiva.');
        
        $service->send('nonexistent', 'test@test.com');
    }

    /** @test */
    public function email_service_throws_exception_for_missing_required_variables()
    {
        EmailTemplate::create([
            'name' => 'Test Template',
            'key' => 'required_vars_test',
            'subject' => 'Test',
            'content_html' => '<p>Hello [name]</p>',
            'variables_schema' => [
                'name' => ['required' => true],
                'email' => ['required' => true]
            ],
            'is_active' => true
        ]);
        
        $service = new EmailTemplateService();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Faltan variables requeridas');
        
        // Solo envía 'name', falta 'email'
        $service->send('required_vars_test', 'test@test.com', ['name' => 'John']);
    }

    /** @test */
    public function email_service_sends_email_with_valid_data()
    {
        // Crear configuración SMTP de prueba
        SmtpSetting::setValue('smtp_from_address', 'from@test.com');
        SmtpSetting::setValue('smtp_from_name', 'Test App');
        
        // Crear plantilla
        EmailTemplate::create([
            'name' => 'Welcome Email',
            'key' => 'welcome_test',
            'subject' => 'Welcome [name]!',
            'content_html' => '<h1>Hello [name]</h1><p>Welcome to [app_name]!</p>',
            'variables_schema' => [
                'name' => ['required' => true],
                'app_name' => ['required' => false]
            ],
            'is_active' => true
        ]);
        
        $service = new EmailTemplateService();
        
        // No debería lanzar excepción
        $service->send('welcome_test', 'user@test.com', [
            'name' => 'John Doe',
            'app_name' => 'Test App'
        ]);
        
        // Verificar que se intentó enviar
        Mail::assertSent(function ($mail) {
            return true;
        });
    }

    /** @test */
    public function email_template_api_requires_authentication()
    {
        $response = $this->getJson('/api/email-templates');
        
        $response->assertStatus(401);
    }

    /** @test */
    public function smtp_settings_api_requires_authentication()
    {
        $response = $this->getJson('/api/smtp/settings');
        
        $response->assertStatus(401);
    }

    /** @test */
    public function shortcodes_are_replaced_correctly()
    {
        $template = EmailTemplate::create([
            'name' => 'Shortcode Test',
            'key' => 'shortcode_test',
            'subject' => 'Order #[order_id] Confirmed',
            'content_html' => '<p>Hi [name],</p><p>Your order #[order_id] for [amount] has been confirmed.</p>',
            'variables_schema' => [
                'name' => ['required' => true],
                'order_id' => ['required' => true],
                'amount' => ['required' => true]
            ],
            'is_active' => true
        ]);
        
        // Usar reflexión para probar el método protegido
        $service = new EmailTemplateService();
        $method = new \ReflectionMethod($service, 'applyShortcodes');
        $method->setAccessible(true);
        
        $content = '<p>Hi [name],</p><p>Your order #[order_id] for [amount] has been confirmed.</p>';
        $data = [
            'name' => 'John',
            'order_id' => '12345',
            'amount' => '$99.99'
        ];
        
        $result = $method->invoke($service, $content, $data);
        
        $this->assertStringContainsString('Hi John', $result);
        $this->assertStringContainsString('#12345', $result);
        $this->assertStringContainsString('$99.99', $result);
        $this->assertStringNotContainsString('[name]', $result);
        $this->assertStringNotContainsString('[order_id]', $result);
        $this->assertStringNotContainsString('[amount]', $result);
    }
}