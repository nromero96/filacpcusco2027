<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RegistrationUploadSecurityTest extends TestCase
{
    public function test_guest_cannot_upload_registration_documents(): void
    {
        $response = $this->post('/upload', [
            'document_file' => UploadedFile::fake()->create('documento.pdf', 100, 'application/pdf'),
        ]);

        $response->assertForbidden();
    }
}
