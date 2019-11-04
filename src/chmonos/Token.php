<?php
namespace ryunosuke\chmonos;

/**
 * CSRF トークンクラス
 */
class Token
{
    use Mixin\Htmlable;

    /** @var string input 名 */
    private $name;

    /** @var string トークン */
    private $value;

    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getToken()
    {
        return $this->value ?? $this->value = sha1($this->name . sha1(session_id()));
    }

    public function validate()
    {
        if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            return true;
        }
        if (strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHTTPREQUEST') {
            return true;
        }
        if (hash_equals($this->getToken(), $_POST[$this->name] ?? '')) {
            return true;
        }
        return false;
    }

    public function render()
    {
        $name = $this->escapeHtml($this->name);
        $token = $this->escapeHtml($this->getToken());
        return "<input type='hidden' name='$name' value='$token'>";
    }
}
