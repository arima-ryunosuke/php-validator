<?php
namespace ryunosuke\chmonos;

/**
 * アップロードファイルのクラス
 */
class UploadedFile
{
    private string $fullPath;
    private string $realPath;
    private string $name;
    private string $type;
    private int    $size;

    public function __construct(array $filearray)
    {
        $this->fullPath = $filearray['full_path'] ?? '';
        $this->realPath = $filearray['tmp_name'];
        $this->name = $filearray['name'];
        $this->type = $filearray['type'];
        $this->size = $filearray['size'];
    }

    public function __toString(): string
    {
        return $this->realPath;
    }

    public function getFullpath(): string
    {
        return $this->fullPath;
    }

    public function getRealPath(): string
    {
        return $this->realPath;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRealType(): string
    {
        return mime_content_type($this->realPath);
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function isUploaded(): bool
    {
        return is_uploaded_file($this->realPath);
    }
}
