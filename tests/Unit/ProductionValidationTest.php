<?php

namespace Tests\Unit;

use App\Rules\PhoneNumber;
use App\Rules\ValidRuc;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ProductionValidationTest extends TestCase
{
    public function test_valid_peruvian_ruc_passes(): void
    {
        $this->assertTrue((new ValidRuc)->passes('invoice_ruc', '20131312955'));
    }

    public function test_ruc_with_wrong_check_digit_fails(): void
    {
        $this->assertFalse((new ValidRuc)->passes('invoice_ruc', '20131312954'));
        $this->assertFalse((new ValidRuc)->passes('invoice_ruc', 'ABCDEFGHIJK'));
        $this->assertFalse((new ValidRuc)->passes('invoice_ruc', '00131312955'));
    }

    public function test_international_phone_formats_pass(): void
    {
        $rule = new PhoneNumber;

        $this->assertTrue($rule->passes('phone', '+51 987 654 321'));
        $this->assertTrue($rule->passes('phone', '(01) 765-4321'));
    }

    public function test_invalid_phone_formats_fail(): void
    {
        $rule = new PhoneNumber;

        $this->assertFalse($rule->passes('phone', '12345'));
        $this->assertFalse($rule->passes('phone', '+51 CALL-NOW'));
        $this->assertFalse($rule->passes('phone', '1234567890123456'));
    }

    public function test_registration_document_rejects_executable_files(): void
    {
        $validator = Validator::make([
            'document_file' => UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload'),
        ], [
            'document_file' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_registration_document_rejects_files_larger_than_ten_mb(): void
    {
        $validator = Validator::make([
            'document_file' => UploadedFile::fake()->create('documento.pdf', 10241, 'application/pdf'),
        ], [
            'document_file' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $this->assertTrue($validator->fails());
    }
}
