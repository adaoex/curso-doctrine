<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $diretorio;

    public function __construct($diretorio)
    {
        $this->diretorio = $diretorio;
    }

    public function upload(UploadedFile $arquivo)
    {
        $nomeArquivo = md5(uniqid()).'.'.$arquivo->guessExtension();

        $file->move($this->diretorio, $nomeArquivo);

        return $nomeArquivo;
    }
    
}
