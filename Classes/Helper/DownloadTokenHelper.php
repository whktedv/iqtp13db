<?php
namespace Ud\Iqtp13db\Helper;

use TYPO3\CMS\Core\SingletonInterface;

class DownloadTokenHelper implements SingletonInterface
{
    private const SESSION_KEY = 'iqtp13db_download_tokens';
    private const TOKEN_LIFETIME = 3600; // 60 Minuten
    
    public function __construct()
    {
        // Session starten, falls noch nicht aktiv
        $this->ensureSessionStarted();
    }
    
    /**
     * Generiert einen sicheren Download-Token
     */
    public function generateToken(int $fileUid, int $dokumentUid, int $teilnehmerUid): string
    {
        $this->ensureSessionStarted();
        
        $token = bin2hex(random_bytes(32));
        $tokenData = [
            'fileUid' => $fileUid,
            'dokumentUid' => $dokumentUid,
            'teilnehmerUid' => $teilnehmerUid,
            'created' => time(),
            'expires' => time() + self::TOKEN_LIFETIME
        ];
        
        // In Session speichern
        $this->saveTokenToSession($token, $tokenData);
        
        return $token;
    }
    
    /**
     * Validiert einen Token und gibt die Daten zurück
     */
    public function validateToken(string $token): ?array
    {
        $this->ensureSessionStarted();
        
        $tokens = $this->getTokensFromSession();
        
        if (!isset($tokens[$token])) {
            return null;
        }
        
        $tokenData = $tokens[$token];
        
        // Prüfe Ablauf
        if ($tokenData['expires'] < time()) {
            $this->removeToken($token);
            return null;
        }
        
        return $tokenData;
    }
    
    /**
     * Entfernt einen Token (z.B. nach einmaligem Download)
     */
    public function removeToken(string $token): void
    {
        $this->ensureSessionStarted();
        
        $tokens = $this->getTokensFromSession();
        unset($tokens[$token]);
        $this->saveAllTokens($tokens);
    }
    
    /**
     * Bereinigt abgelaufene Tokens
     */
    public function cleanupExpiredTokens(): void
    {
        $this->ensureSessionStarted();
        
        $tokens = $this->getTokensFromSession();
        $currentTime = time();
        
        foreach ($tokens as $token => $data) {
            if ($data['expires'] < $currentTime) {
                unset($tokens[$token]);
            }
        }
        
        $this->saveAllTokens($tokens);
    }
    
    private function saveTokenToSession(string $token, array $data): void
    {
        $tokens = $this->getTokensFromSession();
        $tokens[$token] = $data;
        $this->saveAllTokens($tokens);
    }
    
    private function getTokensFromSession(): array
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
        
        return $_SESSION[self::SESSION_KEY];
    }
    
    private function saveAllTokens(array $tokens): void
    {
        $_SESSION[self::SESSION_KEY] = $tokens;
    }
    
    /**
     * Stellt sicher, dass eine Session aktiv ist
     */
    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Session-Cookie-Parameter setzen (httponly, secure wenn HTTPS)
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
            
            session_set_cookie_params([
                'lifetime' => 0, // Session-Cookie (endet mit Browser-Schließung)
                'path' => '/',
                'domain' => '',
                'secure' => $isSecure,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            
            session_start();
        }
    }
}