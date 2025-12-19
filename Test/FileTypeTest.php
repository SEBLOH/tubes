<?php
use PHPUnit\Framework\TestCase;

class FileTypeTest extends TestCase
{

    private $projectFiles = [
        'index.html',
        'pokedex.html',
        'collection.html',
    ];
    // 1
    public function test_files_exist()
    {
        foreach ($this->projectFiles as $file) {
            $this->assertFileExists($file, "File $file tidak ditemukan!");
        }
    }

    //2
    public function test_html_files_contain_html_tags()
    {
        foreach ($this->projectFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'html') {
                $content = file_get_contents($file);

                $this->assertMatchesRegularExpression(
                    '/(html|head|body|div|p|span|i|section)/',
                    $content,
                    "File $file bukan HTML yang valid!"
                );
            }
        }
    }

    //3
    public function test_api_integration_script_exists()
    {
        $filesToCheck = ['pokedex.html', 'collection.html'];
        foreach ($filesToCheck as $file) {
            $content = file_get_contents($file);
            $this->assertStringContainsString(
                'pokeapi.co/api/v2',
                $content,
                "File $file tidak memiliki script koneksi ke PokeAPI!"
            );
        }
    }

    //4
    public function test_navbar_links_are_correct()
    {
        $content = file_get_contents('index.html');
        $this->assertStringContainsString('href="collection.html"', $content, "Link Collection di Navbar index.html salah!");
        $this->assertStringContainsString('href="pokedex.html"', $content, "Link Pokedex Guide di Navbar index.html salah!");
    }

    //5
    public function test_page_titles_are_present()
    {
        foreach ($this->projectFiles as $file) {
            $content = file_get_contents($file);
            $this->assertMatchesRegularExpression(
                '/<title>.*<\/title>/i',
                $content,
                "File $file tidak memiliki tag <title>!"
            );
        }
    }
}