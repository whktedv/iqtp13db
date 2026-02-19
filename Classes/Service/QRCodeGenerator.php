<?php

declare(strict_types=1);

namespace Ud\Iqtp13db\Service;

/**
 * QRCodeGenerator – DSGVO-konforme QR-Code-Bibliothek
 * Vollständig neu implementiert, validiert gegen ISO 18004.
 *
 * PHP 8.2, kein ext-* außer ext-gd (nur PNG-Renderer).
 * SVG-Renderer benötigt kein GD.
 *
 * Matrix-Wert-Schema:
 *   -1 = frei (noch nicht belegt)
 *    0 = helles Modul (gesetzt)
 *    1 = dunkles Modul (gesetzt)
 *
 * Funktionsmodule (Finder, Timing, Alignment, Format, Version, Dark)
 * werden in einem separaten Set verfolgt und bei der Maskierung übersprungen.
 */
final class QRCodeGenerator
{
    // ─── ECC-Konstanten ───────────────────────────────────────────────────────
    public const ECC_L = 'L';
    public const ECC_M = 'M';
    public const ECC_Q = 'Q';
    public const ECC_H = 'H';

    private const FREE = -1;

    // ─── Kapazitätstabelle (Byte-Mode, Versionen 1–40) ────────────────────────
    private const DATA_CAP = [
        1  => [self::ECC_L=>17,  self::ECC_M=>14,  self::ECC_Q=>11,  self::ECC_H=>7],
        2  => [self::ECC_L=>32,  self::ECC_M=>26,  self::ECC_Q=>20,  self::ECC_H=>14],
        3  => [self::ECC_L=>53,  self::ECC_M=>42,  self::ECC_Q=>32,  self::ECC_H=>24],
        4  => [self::ECC_L=>78,  self::ECC_M=>62,  self::ECC_Q=>46,  self::ECC_H=>34],
        5  => [self::ECC_L=>106, self::ECC_M=>84,  self::ECC_Q=>60,  self::ECC_H=>44],
        6  => [self::ECC_L=>134, self::ECC_M=>106, self::ECC_Q=>74,  self::ECC_H=>58],
        7  => [self::ECC_L=>154, self::ECC_M=>122, self::ECC_Q=>86,  self::ECC_H=>64],
        8  => [self::ECC_L=>192, self::ECC_M=>154, self::ECC_Q=>108, self::ECC_H=>84],
        9  => [self::ECC_L=>230, self::ECC_M=>180, self::ECC_Q=>130, self::ECC_H=>98],
        10 => [self::ECC_L=>271, self::ECC_M=>213, self::ECC_Q=>151, self::ECC_H=>119],
        11 => [self::ECC_L=>321, self::ECC_M=>251, self::ECC_Q=>177, self::ECC_H=>137],
        12 => [self::ECC_L=>367, self::ECC_M=>287, self::ECC_Q=>203, self::ECC_H=>155],
        13 => [self::ECC_L=>425, self::ECC_M=>331, self::ECC_Q=>241, self::ECC_H=>177],
        14 => [self::ECC_L=>458, self::ECC_M=>362, self::ECC_Q=>258, self::ECC_H=>194],
        15 => [self::ECC_L=>520, self::ECC_M=>412, self::ECC_Q=>292, self::ECC_H=>220],
        16 => [self::ECC_L=>586, self::ECC_M=>450, self::ECC_Q=>322, self::ECC_H=>250],
        17 => [self::ECC_L=>644, self::ECC_M=>504, self::ECC_Q=>364, self::ECC_H=>280],
        18 => [self::ECC_L=>718, self::ECC_M=>560, self::ECC_Q=>394, self::ECC_H=>310],
        19 => [self::ECC_L=>792, self::ECC_M=>624, self::ECC_Q=>442, self::ECC_H=>338],
        20 => [self::ECC_L=>858, self::ECC_M=>666, self::ECC_Q=>482, self::ECC_H=>382],
        21 => [self::ECC_L=>929, self::ECC_M=>711, self::ECC_Q=>509, self::ECC_H=>403],
        22 => [self::ECC_L=>1003,self::ECC_M=>779, self::ECC_Q=>565, self::ECC_H=>439],
        23 => [self::ECC_L=>1091,self::ECC_M=>857, self::ECC_Q=>611, self::ECC_H=>461],
        24 => [self::ECC_L=>1171,self::ECC_M=>911, self::ECC_Q=>661, self::ECC_H=>511],
        25 => [self::ECC_L=>1273,self::ECC_M=>997, self::ECC_Q=>715, self::ECC_H=>535],
        26 => [self::ECC_L=>1367,self::ECC_M=>1059,self::ECC_Q=>751, self::ECC_H=>593],
        27 => [self::ECC_L=>1465,self::ECC_M=>1125,self::ECC_Q=>805, self::ECC_H=>625],
        28 => [self::ECC_L=>1528,self::ECC_M=>1190,self::ECC_Q=>868, self::ECC_H=>658],
        29 => [self::ECC_L=>1628,self::ECC_M=>1264,self::ECC_Q=>908, self::ECC_H=>698],
        30 => [self::ECC_L=>1732,self::ECC_M=>1370,self::ECC_Q=>982, self::ECC_H=>742],
        31 => [self::ECC_L=>1840,self::ECC_M=>1452,self::ECC_Q=>1030,self::ECC_H=>790],
        32 => [self::ECC_L=>1952,self::ECC_M=>1538,self::ECC_Q=>1112,self::ECC_H=>842],
        33 => [self::ECC_L=>2068,self::ECC_M=>1628,self::ECC_Q=>1168,self::ECC_H=>898],
        34 => [self::ECC_L=>2188,self::ECC_M=>1722,self::ECC_Q=>1228,self::ECC_H=>958],
        35 => [self::ECC_L=>2303,self::ECC_M=>1809,self::ECC_Q=>1283,self::ECC_H=>983],
        36 => [self::ECC_L=>2431,self::ECC_M=>1911,self::ECC_Q=>1351,self::ECC_H=>1051],
        37 => [self::ECC_L=>2563,self::ECC_M=>1989,self::ECC_Q=>1423,self::ECC_H=>1093],
        38 => [self::ECC_L=>2699,self::ECC_M=>2099,self::ECC_Q=>1499,self::ECC_H=>1139],
        39 => [self::ECC_L=>2809,self::ECC_M=>2213,self::ECC_Q=>1579,self::ECC_H=>1219],
        40 => [self::ECC_L=>2953,self::ECC_M=>2331,self::ECC_Q=>1663,self::ECC_H=>1273],
    ];

    // ─── ECC-Block-Tabelle [version][ecc] = [ecPerBlock, [[cnt,dataCw]...], [[cnt,dataCw]...]] ─
    private const ECC_BLK = [
        1  => [self::ECC_L=>[7,[[1,19]],[]], self::ECC_M=>[10,[[1,16]],[]], self::ECC_Q=>[13,[[1,13]],[]], self::ECC_H=>[17,[[1,9]],[]]],
        2  => [self::ECC_L=>[10,[[1,34]],[]], self::ECC_M=>[16,[[1,28]],[]], self::ECC_Q=>[22,[[1,22]],[]], self::ECC_H=>[28,[[1,16]],[]]],
        3  => [self::ECC_L=>[15,[[1,55]],[]], self::ECC_M=>[26,[[1,44]],[]], self::ECC_Q=>[18,[[2,17]],[]], self::ECC_H=>[22,[[2,13]],[]]],
        4  => [self::ECC_L=>[20,[[1,80]],[]], self::ECC_M=>[18,[[2,32]],[]], self::ECC_Q=>[26,[[2,24]],[]], self::ECC_H=>[16,[[4,9]],[]]],
        5  => [self::ECC_L=>[26,[[1,108]],[]], self::ECC_M=>[24,[[2,43]],[]], self::ECC_Q=>[18,[[2,15]],[[2,16]]], self::ECC_H=>[22,[[2,11]],[[2,12]]]],
        6  => [self::ECC_L=>[18,[[2,68]],[]], self::ECC_M=>[16,[[4,27]],[]], self::ECC_Q=>[24,[[4,19]],[]], self::ECC_H=>[28,[[4,15]],[]]],
        7  => [self::ECC_L=>[20,[[2,78]],[]], self::ECC_M=>[18,[[4,31]],[]], self::ECC_Q=>[18,[[2,14]],[[4,15]]], self::ECC_H=>[26,[[4,13]],[[1,14]]]],
        8  => [self::ECC_L=>[24,[[2,97]],[]], self::ECC_M=>[22,[[2,38]],[[2,39]]], self::ECC_Q=>[22,[[4,18]],[[2,19]]], self::ECC_H=>[26,[[4,14]],[[2,15]]]],
        9  => [self::ECC_L=>[30,[[2,116]],[]], self::ECC_M=>[22,[[3,36]],[[2,37]]], self::ECC_Q=>[20,[[4,16]],[[4,17]]], self::ECC_H=>[24,[[4,12]],[[4,13]]]],
        10 => [self::ECC_L=>[18,[[2,68]],[[2,69]]], self::ECC_M=>[26,[[4,43]],[[1,44]]], self::ECC_Q=>[24,[[6,19]],[[2,20]]], self::ECC_H=>[28,[[6,15]],[[2,16]]]],
        11 => [self::ECC_L=>[20,[[4,81]],[]], self::ECC_M=>[30,[[1,50]],[[4,51]]], self::ECC_Q=>[28,[[4,22]],[[4,23]]], self::ECC_H=>[24,[[3,12]],[[8,13]]]],
        12 => [self::ECC_L=>[24,[[2,92]],[[2,93]]], self::ECC_M=>[22,[[6,36]],[[2,37]]], self::ECC_Q=>[26,[[4,20]],[[6,21]]], self::ECC_H=>[28,[[7,14]],[[4,15]]]],
        13 => [self::ECC_L=>[26,[[4,107]],[]], self::ECC_M=>[22,[[8,37]],[[1,38]]], self::ECC_Q=>[24,[[8,20]],[[4,21]]], self::ECC_H=>[22,[[12,11]],[[4,12]]]],
        14 => [self::ECC_L=>[30,[[3,115]],[[1,116]]], self::ECC_M=>[24,[[4,40]],[[5,41]]], self::ECC_Q=>[20,[[11,16]],[[5,17]]], self::ECC_H=>[24,[[11,12]],[[5,13]]]],
        15 => [self::ECC_L=>[22,[[5,87]],[[1,88]]], self::ECC_M=>[24,[[5,41]],[[5,42]]], self::ECC_Q=>[30,[[5,24]],[[7,25]]], self::ECC_H=>[24,[[11,12]],[[7,13]]]],
        16 => [self::ECC_L=>[24,[[5,98]],[[1,99]]], self::ECC_M=>[28,[[7,45]],[[3,46]]], self::ECC_Q=>[24,[[15,19]],[[2,20]]], self::ECC_H=>[30,[[3,15]],[[13,16]]]],
        17 => [self::ECC_L=>[28,[[1,107]],[[5,108]]], self::ECC_M=>[28,[[10,46]],[[1,47]]], self::ECC_Q=>[28,[[1,22]],[[15,23]]], self::ECC_H=>[28,[[2,14]],[[17,15]]]],
        18 => [self::ECC_L=>[30,[[5,120]],[[1,121]]], self::ECC_M=>[26,[[9,43]],[[4,44]]], self::ECC_Q=>[28,[[17,22]],[[1,23]]], self::ECC_H=>[28,[[2,14]],[[19,15]]]],
        19 => [self::ECC_L=>[28,[[3,113]],[[4,114]]], self::ECC_M=>[26,[[3,44]],[[11,45]]], self::ECC_Q=>[26,[[17,21]],[[4,22]]], self::ECC_H=>[26,[[9,13]],[[16,14]]]],
        20 => [self::ECC_L=>[28,[[3,107]],[[5,108]]], self::ECC_M=>[26,[[3,41]],[[13,42]]], self::ECC_Q=>[30,[[15,25]],[[5,26]]], self::ECC_H=>[28,[[15,15]],[[10,16]]]],
        21 => [self::ECC_L=>[28,[[4,116]],[[4,117]]], self::ECC_M=>[26,[[17,42]],[]], self::ECC_Q=>[28,[[17,22]],[[6,23]]], self::ECC_H=>[30,[[19,16]],[[6,17]]]],
        22 => [self::ECC_L=>[28,[[2,111]],[[7,112]]], self::ECC_M=>[28,[[17,46]],[]], self::ECC_Q=>[30,[[7,24]],[[16,25]]], self::ECC_H=>[24,[[34,13]],[]]],
        23 => [self::ECC_L=>[30,[[4,121]],[[5,122]]], self::ECC_M=>[28,[[4,47]],[[14,48]]], self::ECC_Q=>[30,[[11,24]],[[14,25]]], self::ECC_H=>[30,[[16,15]],[[14,16]]]],
        24 => [self::ECC_L=>[30,[[6,117]],[[4,118]]], self::ECC_M=>[28,[[6,45]],[[14,46]]], self::ECC_Q=>[30,[[11,24]],[[16,25]]], self::ECC_H=>[30,[[30,16]],[[2,17]]]],
        25 => [self::ECC_L=>[26,[[8,106]],[[4,107]]], self::ECC_M=>[28,[[8,47]],[[13,48]]], self::ECC_Q=>[30,[[7,24]],[[22,25]]], self::ECC_H=>[30,[[22,15]],[[13,16]]]],
        26 => [self::ECC_L=>[28,[[10,114]],[[2,115]]], self::ECC_M=>[28,[[19,46]],[[4,47]]], self::ECC_Q=>[28,[[28,22]],[[6,23]]], self::ECC_H=>[30,[[33,16]],[[4,17]]]],
        27 => [self::ECC_L=>[30,[[8,122]],[[4,123]]], self::ECC_M=>[28,[[22,45]],[[3,46]]], self::ECC_Q=>[30,[[8,23]],[[26,24]]], self::ECC_H=>[30,[[12,15]],[[28,16]]]],
        28 => [self::ECC_L=>[30,[[3,117]],[[10,118]]], self::ECC_M=>[28,[[3,45]],[[23,46]]], self::ECC_Q=>[30,[[4,24]],[[31,25]]], self::ECC_H=>[30,[[11,15]],[[31,16]]]],
        29 => [self::ECC_L=>[30,[[7,116]],[[7,117]]], self::ECC_M=>[28,[[21,45]],[[7,46]]], self::ECC_Q=>[30,[[1,23]],[[37,24]]], self::ECC_H=>[30,[[19,15]],[[26,16]]]],
        30 => [self::ECC_L=>[30,[[5,115]],[[10,116]]], self::ECC_M=>[28,[[19,45]],[[10,46]]], self::ECC_Q=>[30,[[15,24]],[[25,25]]], self::ECC_H=>[30,[[23,15]],[[25,16]]]],
        31 => [self::ECC_L=>[30,[[13,115]],[[3,116]]], self::ECC_M=>[28,[[2,45]],[[29,46]]], self::ECC_Q=>[30,[[42,24]],[[1,25]]], self::ECC_H=>[30,[[23,15]],[[28,16]]]],
        32 => [self::ECC_L=>[30,[[17,115]],[]], self::ECC_M=>[28,[[10,45]],[[23,46]]], self::ECC_Q=>[30,[[10,24]],[[35,25]]], self::ECC_H=>[30,[[19,15]],[[35,16]]]],
        33 => [self::ECC_L=>[30,[[17,115]],[[1,116]]], self::ECC_M=>[28,[[14,45]],[[21,46]]], self::ECC_Q=>[30,[[29,24]],[[19,25]]], self::ECC_H=>[30,[[11,15]],[[46,16]]]],
        34 => [self::ECC_L=>[30,[[13,115]],[[6,116]]], self::ECC_M=>[28,[[14,45]],[[23,46]]], self::ECC_Q=>[30,[[44,24]],[[7,25]]], self::ECC_H=>[30,[[59,16]],[[1,17]]]],
        35 => [self::ECC_L=>[30,[[12,121]],[[7,122]]], self::ECC_M=>[28,[[12,45]],[[26,46]]], self::ECC_Q=>[30,[[39,24]],[[14,25]]], self::ECC_H=>[30,[[22,15]],[[41,16]]]],
        36 => [self::ECC_L=>[30,[[6,121]],[[14,122]]], self::ECC_M=>[28,[[6,45]],[[34,46]]], self::ECC_Q=>[30,[[46,24]],[[10,25]]], self::ECC_H=>[30,[[2,15]],[[64,16]]]],
        37 => [self::ECC_L=>[30,[[17,122]],[[4,123]]], self::ECC_M=>[28,[[29,45]],[[14,46]]], self::ECC_Q=>[30,[[49,24]],[[10,25]]], self::ECC_H=>[30,[[24,15]],[[46,16]]]],
        38 => [self::ECC_L=>[30,[[4,122]],[[18,123]]], self::ECC_M=>[28,[[13,45]],[[32,46]]], self::ECC_Q=>[30,[[48,24]],[[14,25]]], self::ECC_H=>[30,[[42,15]],[[32,16]]]],
        39 => [self::ECC_L=>[30,[[20,117]],[[4,118]]], self::ECC_M=>[28,[[40,45]],[[7,46]]], self::ECC_Q=>[30,[[43,24]],[[22,25]]], self::ECC_H=>[30,[[10,15]],[[67,16]]]],
        40 => [self::ECC_L=>[30,[[19,118]],[[6,119]]], self::ECC_M=>[28,[[18,45]],[[31,46]]], self::ECC_Q=>[30,[[34,24]],[[34,25]]], self::ECC_H=>[30,[[20,15]],[[61,16]]]],
    ];

    // ─── Alignment-Pattern-Positionen ─────────────────────────────────────────
    private const ALIGN = [
        2=>[6,18], 3=>[6,22], 4=>[6,26], 5=>[6,30], 6=>[6,34],
        7=>[6,22,38], 8=>[6,24,42], 9=>[6,26,46], 10=>[6,28,50],
        11=>[6,30,54], 12=>[6,32,58], 13=>[6,34,62],
        14=>[6,26,46,66], 15=>[6,26,48,70], 16=>[6,26,50,74],
        17=>[6,30,54,78], 18=>[6,30,56,82], 19=>[6,30,58,86], 20=>[6,34,62,90],
        21=>[6,28,50,72,94], 22=>[6,26,50,74,98], 23=>[6,30,54,78,102],
        24=>[6,28,54,80,106], 25=>[6,32,58,84,110], 26=>[6,30,58,86,114],
        27=>[6,34,62,90,118], 28=>[6,26,50,74,98,122], 29=>[6,30,54,78,102,126],
        30=>[6,26,52,78,104,130], 31=>[6,30,56,82,108,134], 32=>[6,34,60,86,112,138],
        33=>[6,30,58,86,114,142], 34=>[6,34,62,90,118,146],
        35=>[6,30,54,78,102,126,150], 36=>[6,24,50,76,102,128,154],
        37=>[6,28,54,80,106,132,158], 38=>[6,32,58,84,110,136,162],
        39=>[6,26,54,82,110,138,166], 40=>[6,30,58,86,114,142,170],
    ];

    // ─── Format-Info-Bits (vorberechnet inkl. BCH + XOR-Maske) ───────────────
    private const FMT_INFO = [
        self::ECC_L => [
            0=>'111011111000100', 1=>'111001011110011', 2=>'111110110101010', 3=>'111100010011101',
            4=>'110011000101111', 5=>'110001100011000', 6=>'110110001000001', 7=>'110100101110110',
        ],
        self::ECC_M => [
            0=>'101010000010010', 1=>'101000100100101', 2=>'101111001111100', 3=>'101101101001011',
            4=>'100010111111001', 5=>'100000011001110', 6=>'100111110010111', 7=>'100101010100000',
        ],
        self::ECC_Q => [
            0=>'011010101011111', 1=>'011000001101000', 2=>'011111100110001', 3=>'011101000000110',
            4=>'010010010110100', 5=>'010000110000011', 6=>'010111011011010', 7=>'010101111101101',
        ],
        self::ECC_H => [
            0=>'001011010001001', 1=>'001001110111110', 2=>'001110011100111', 3=>'001100111010000',
            4=>'000011101100010', 5=>'000001001010101', 6=>'000110100001100', 7=>'000100000111011',
        ],
    ];

    // ─── Versions-Info-Bits (Version 7–40, 18 Bit) ───────────────────────────
    private const VER_INFO = [
        7=>'000111110010010100', 8=>'001000010110111100', 9=>'001001101010011001',
        10=>'001010010011010011',11=>'001011101111110110',12=>'001100011101100010',
        13=>'001101100001000111',14=>'001110011000001101',15=>'001111100100101000',
        16=>'010000101101111000',17=>'010001010001011101',18=>'010010101000010111',
        19=>'010011010100110010',20=>'010100100110100110',21=>'010101011010000011',
        22=>'010110100011001001',23=>'010111011111101100',24=>'011000111011000100',
        25=>'011001000111100001',26=>'011010111110101011',27=>'011011000010001110',
        28=>'011100110000011010',29=>'011101001100111111',30=>'011110110101110101',
        31=>'011111001001010000',32=>'100000100111010101',33=>'100001011011110000',
        34=>'100010100010111010',35=>'100011011110011111',36=>'100100101100001011',
        37=>'100101010000101110',38=>'100110101001100100',39=>'100111010101000001',
        40=>'101000110001101001',
    ];

    // ─── GF(256)-Tabellen ─────────────────────────────────────────────────────
    /** @var int[] */
    private array $exp = [];
    /** @var int[] */
    private array $log = [];

    public function __construct()
    {
        $this->buildGF();
    }

    // =========================================================================
    // Public API
    // =========================================================================

    /**
     * Erzeugt QR-Code als PNG-Binärdaten (raw bytes).
     */
    public function generatePng(
        string $data,
        string $ecc = self::ECC_M,
        int    $scale = 10,
        int    $margin = 4
    ): string {
        $matrix = $this->buildMatrix($data, $ecc);
        return $this->renderPng($matrix, $scale, $margin);
    }

    /**
     * Erzeugt QR-Code als SVG-String.
     */
    public function generateSvg(
        string $data,
        string $ecc = self::ECC_M,
        int    $margin = 4
    ): string {
        $matrix = $this->buildMatrix($data, $ecc);
        return $this->renderSvg($matrix, $margin);
    }

    /**
     * Gibt PNG als Base64-Data-URI zurück (direkt als <img src="..."> nutzbar).
     */
    public function generateBase64Png(
        string $data,
        string $ecc = self::ECC_M,
        int    $scale = 10,
        int    $margin = 4
    ): string {
        return 'data:image/png;base64,' . base64_encode(
            $this->generatePng($data, $ecc, $scale, $margin)
        );
    }

    /**
     * Gibt SVG als Base64-Data-URI zurück.
     */
    public function generateBase64Svg(
        string $data,
        string $ecc = self::ECC_M,
        int    $margin = 4
    ): string {
        return 'data:image/svg+xml;base64,' . base64_encode(
            $this->generateSvg($data, $ecc, $margin)
        );
    }

    /**
     * Speichert QR-Code als Datei (PNG oder SVG).
     */
    public function saveToFile(
        string $filePath,
        string $format = 'png',
        string $data = '',
        string $ecc = self::ECC_M,
        int    $scale = 10,
        int    $margin = 4
    ): bool {
        $content = strtolower($format) === 'svg'
            ? $this->generateSvg($data, $ecc, $margin)
            : $this->generatePng($data, $ecc, $scale, $margin);

        $dir = dirname($filePath);
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new \RuntimeException("Verzeichnis konnte nicht erstellt werden: $dir");
        }

        return file_put_contents($filePath, $content) !== false;
    }

    // =========================================================================
    // Matrix-Aufbau
    // =========================================================================

    /**
     * Baut die vollständige, finale QR-Code-Matrix auf.
     *
     * @return array{matrix: int[][], size: int}
     */
    private function buildMatrix(string $data, string $ecc): array
    {
        $this->validateEcc($ecc);

        $version = $this->determineVersion($data, $ecc);
        $size    = $version * 4 + 17;

        // Matrix mit FREE initialisieren
        $mat  = array_fill(0, $size, array_fill(0, $size, self::FREE));
        // Menge der Funktionsmodul-Koordinaten als "r,c"-Strings
        $func = [];

        $setFunc = function (int $r, int $c, int $v) use (&$mat, &$func): void {
            $mat[$r][$c]    = $v;
            $func["$r,$c"] = true;
        };

        // Finder-Patterns
        $this->placeFinder($mat, $func, 0, 0, $setFunc);
        $this->placeFinder($mat, $func, 0, $size - 7, $setFunc);
        $this->placeFinder($mat, $func, $size - 7, 0, $setFunc);

        // Separatoren
        $this->placeSeparators($mat, $func, $size, $setFunc);

        // Timing-Patterns
        $this->placeTiming($mat, $func, $size, $setFunc);

        // Dark Module
        $setFunc(4 * $version + 9, 8, 1);

        // Alignment-Patterns
        $this->placeAlignment($mat, $func, $version, $size, $setFunc);

        // Versions-Info (ab Version 7)
        if ($version >= 7) {
            $this->placeVersionInfo($mat, $func, $version, $size, $setFunc);
        }

        // Format-Info reservieren
        $this->reserveFormat($mat, $func, $size, $setFunc);

        // Daten-Codewords kodieren und platzieren
        $codewords = $this->encodeData($data, $ecc, $version);
        $this->placeData($mat, $codewords, $size);

        // Beste Maske bestimmen
        $bestMask = $this->selectMask($mat, $func, $size);

        // Maske anwenden
        $mat = $this->applyMask($mat, $func, $bestMask, $size);

        // Format-Info schreiben
        $this->writeFormatInfo($mat, $ecc, $bestMask, $size);

        return ['matrix' => $mat, 'size' => $size];
    }

    // ─── Finder-Pattern ───────────────────────────────────────────────────────

    private function placeFinder(
        array &$mat, array &$func,
        int $tr, int $tc,
        callable $setFunc
    ): void {
        for ($r = 0; $r < 7; $r++) {
            for ($c = 0; $c < 7; $c++) {
                // Rand (Zeile 0/6 oder Spalte 0/6) ODER inneres 3×3
                $dark = ($r === 0 || $r === 6 || $c === 0 || $c === 6)
                    || ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4);
                $setFunc($tr + $r, $tc + $c, $dark ? 1 : 0);
            }
        }
    }

    private function placeSeparators(
        array &$mat, array &$func, int $size, callable $setFunc
    ): void {
        // Oben-links: Zeile 7 (c=0..7) und Spalte 7 (r=0..7)
        for ($i = 0; $i < 8; $i++) {
            if ($mat[7][$i] === self::FREE) $setFunc(7, $i, 0);
            if ($mat[$i][7] === self::FREE) $setFunc($i, 7, 0);
        }
        // Oben-rechts: Zeile 7 (c=size-8..size-1) und Spalte size-8 (r=0..7)
        for ($i = 0; $i < 8; $i++) {
            if ($mat[7][$size - 8 + $i] === self::FREE) $setFunc(7, $size - 8 + $i, 0);
            if ($mat[$i][$size - 8] === self::FREE)     $setFunc($i, $size - 8, 0);
        }
        // Unten-links: Zeile size-8 (c=0..7) und Spalte 7 (r=size-8..size-1)
        for ($i = 0; $i < 8; $i++) {
            if ($mat[$size - 8][$i] === self::FREE)     $setFunc($size - 8, $i, 0);
            if ($mat[$size - 8 + $i][7] === self::FREE) $setFunc($size - 8 + $i, 7, 0);
        }
    }

    private function placeTiming(
        array &$mat, array &$func, int $size, callable $setFunc
    ): void {
        for ($i = 8; $i < $size - 8; $i++) {
            if ($mat[6][$i] === self::FREE) $setFunc(6, $i, $i % 2 === 0 ? 1 : 0);
            if ($mat[$i][6] === self::FREE) $setFunc($i, 6, $i % 2 === 0 ? 1 : 0);
        }
    }

    private function placeAlignment(
        array &$mat, array &$func, int $version, int $size, callable $setFunc
    ): void {
        if (!isset(self::ALIGN[$version])) {
            return;
        }
        $positions = self::ALIGN[$version];

        foreach ($positions as $cr) {
            foreach ($positions as $cc) {
                // Überlappung mit Finder-Bereichen (inkl. Separator) vermeiden
                if ($cr <= 8 && $cc <= 8)             continue; // oben-links
                if ($cr <= 8 && $cc >= $size - 9)     continue; // oben-rechts
                if ($cr >= $size - 9 && $cc <= 8)     continue; // unten-links

                for ($dr = -2; $dr <= 2; $dr++) {
                    for ($dc = -2; $dc <= 2; $dc++) {
                        $dark = abs($dr) === 2 || abs($dc) === 2
                            || ($dr === 0 && $dc === 0);
                        $setFunc($cr + $dr, $cc + $dc, $dark ? 1 : 0);
                    }
                }
            }
        }
    }

    private function placeVersionInfo(
        array &$mat, array &$func, int $version, int $size, callable $setFunc
    ): void {
        if (!isset(self::VER_INFO[$version])) {
            return;
        }
        $bits = self::VER_INFO[$version];

        // Oben-rechts (3 Spalten × 6 Zeilen)
        for ($i = 0; $i < 18; $i++) {
            $r = (int)($i / 3);
            $c = $size - 11 + ($i % 3);
            $setFunc($r, $c, (int)$bits[$i]);
        }
        // Unten-links (6 Spalten × 3 Zeilen)
        for ($i = 0; $i < 18; $i++) {
            $r = $size - 11 + ($i % 3);
            $c = (int)($i / 3);
            $setFunc($r, $c, (int)$bits[$i]);
        }
    }

    private function reserveFormat(
        array &$mat, array &$func, int $size, callable $setFunc
    ): void {
        // Kopie 1 – Zeile 8 (c=0..5, 7, 8) und Spalte 8 (r=0, 1..5, 7)
        foreach (array_merge(range(0, 5), [7, 8]) as $c) {
            if ($mat[8][$c] === self::FREE) $setFunc(8, $c, 0);
        }
        foreach (array_merge([7, 5, 4, 3, 2, 1, 0]) as $r) {
            if ($mat[$r][8] === self::FREE) $setFunc($r, 8, 0);
        }
        // Kopie 2 – Zeile 8 (c=size-8..size-1) und Spalte 8 (r=size-7..size-1)
        for ($c = $size - 8; $c < $size; $c++) {
            if ($mat[8][$c] === self::FREE) $setFunc(8, $c, 0);
        }
        for ($r = $size - 7; $r < $size; $r++) {
            if ($mat[$r][8] === self::FREE) $setFunc($r, 8, 0);
        }
    }

    // ─── Daten-Enkodierung ────────────────────────────────────────────────────

    private function determineVersion(string $data, string $ecc): int
    {
        $len = strlen($data);
        foreach (self::DATA_CAP as $version => $caps) {
            if ($len <= $caps[$ecc]) {
                return $version;
            }
        }
        throw new \InvalidArgumentException(
            sprintf('Daten zu lang (%d Bytes). Maximum bei ECC %s: %d Bytes.', $len, $ecc, self::DATA_CAP[40][$ecc])
        );
    }

    /** @return int[] Interleaved Codewords */
    private function encodeData(string $data, string $ecc, int $version): array
    {
        $raw = $data; // ISO-8859-1 kompatibel
        $n   = strlen($raw);

        // Byte-Mode-Bitstream aufbauen
        $bits  = '0100'; // Mode-Indikator
        $cntBits = $version <= 9 ? 8 : 16;
        $bits .= str_pad(decbin($n), $cntBits, '0', STR_PAD_LEFT);

        for ($i = 0; $i < $n; $i++) {
            $bits .= str_pad(decbin(ord($raw[$i])), 8, '0', STR_PAD_LEFT);
        }

        [$ecPerBlock, $g1, $g2] = self::ECC_BLK[$version][$ecc];

        $totalCw = 0;
        foreach ([$g1, $g2] as $grp) {
            foreach ($grp as [$cnt, $dw]) {
                $totalCw += $cnt * $dw;
            }
        }
        $totalBits = $totalCw * 8;

        // Terminierung + Byte-Ausrichtung
        $bits .= str_repeat('0', min(4, $totalBits - strlen($bits)));
        while (strlen($bits) % 8 !== 0) {
            $bits .= '0';
        }

        // Padding
        $pads = ['11101100', '00010001'];
        $pi   = 0;
        while (strlen($bits) < $totalBits) {
            $bits .= $pads[$pi++ % 2];
        }

        // In Byte-Array umwandeln
        $bytes = [];
        for ($i = 0; $i < strlen($bits); $i += 8) {
            $bytes[] = (int)bindec(substr($bits, $i, 8));
        }

        // ECC berechnen und interleaven
        $dataBlocks = [];
        $eccBlocks  = [];
        $offset     = 0;

        foreach ([$g1, $g2] as $grp) {
            foreach ($grp as [$cnt, $dw]) {
                for ($i = 0; $i < $cnt; $i++) {
                    $blk          = array_slice($bytes, $offset, $dw);
                    $dataBlocks[] = $blk;
                    $eccBlocks[]  = $this->rsEncode($blk, $ecPerBlock);
                    $offset      += $dw;
                }
            }
        }

        $result = [];
        $maxLen = max(array_map('count', $dataBlocks));
        for ($i = 0; $i < $maxLen; $i++) {
            foreach ($dataBlocks as $blk) {
                if (isset($blk[$i])) $result[] = $blk[$i];
            }
        }
        for ($i = 0; $i < $ecPerBlock; $i++) {
            foreach ($eccBlocks as $blk) {
                if (isset($blk[$i])) $result[] = $blk[$i];
            }
        }

        return $result;
    }

    // ─── Reed-Solomon ─────────────────────────────────────────────────────────

    private function buildGF(): void
    {
        $this->exp = array_fill(0, 512, 0);
        $this->log = array_fill(0, 256, 0);

        $x = 1;
        for ($i = 0; $i < 255; $i++) {
            $this->exp[$i] = $x;
            $this->log[$x] = $i;
            $x = ($x & 0x80) ? (($x << 1) ^ 0x11D) & 0xFF : ($x << 1) & 0xFF;
        }
        for ($i = 255; $i < 512; $i++) {
            $this->exp[$i] = $this->exp[$i - 255];
        }
    }

    private function gfMul(int $a, int $b): int
    {
        if ($a === 0 || $b === 0) return 0;
        return $this->exp[($this->log[$a] + $this->log[$b]) % 255];
    }

    /** Erzeugt das RS-Generator-Polynom vom Grad $n. */
    private function rsGenPoly(int $n): array
    {
        $poly = [1];
        for ($i = 0; $i < $n; $i++) {
            $alpha  = $this->exp[$i];
            $newPoly = array_fill(0, count($poly) + 1, 0);
            foreach ($poly as $j => $c) {
                $newPoly[$j]     ^= $c;
                $newPoly[$j + 1] ^= $this->gfMul($c, $alpha);
            }
            $poly = $newPoly;
        }
        return $poly;
    }

    /** @return int[] ECC-Bytes */
    private function rsEncode(array $data, int $n): array
    {
        $gen = $this->rsGenPoly($n);
        $msg = array_merge($data, array_fill(0, $n, 0));

        for ($i = 0, $dLen = count($data); $i < $dLen; $i++) {
            $c = $msg[$i];
            if ($c !== 0) {
                foreach ($gen as $j => $gj) {
                    $msg[$i + $j] ^= $this->gfMul($gj, $c);
                }
            }
        }

        return array_slice($msg, count($data));
    }

    // ─── Daten platzieren ─────────────────────────────────────────────────────

    private function placeData(array &$mat, array $codewords, int $size): void
    {
        $bits  = '';
        foreach ($codewords as $cw) {
            $bits .= str_pad(decbin($cw), 8, '0', STR_PAD_LEFT);
        }
        $bitLen = strlen($bits);
        $bi     = 0;
        $upward = true;
        $col    = $size - 1;

        while ($col > 0) {
            if ($col === 6) {
                $col--;
            }

            $rows = $upward ? range($size - 1, 0, -1) : range(0, $size - 1);

            foreach ($rows as $row) {
                for ($dc = 0; $dc <= 1; $dc++) {
                    $c = $col - $dc;
                    if ($c >= 0 && $mat[$row][$c] === self::FREE) {
                        $mat[$row][$c] = ($bi < $bitLen) ? (int)$bits[$bi] : 0;
                        $bi++;
                    }
                }
            }

            $upward = !$upward;
            $col   -= 2;
        }
    }

    // ─── Maskierung ───────────────────────────────────────────────────────────

    private function maskCondition(int $mask, int $r, int $c): bool
    {
        return match ($mask) {
            0 => ($r + $c) % 2 === 0,
            1 => $r % 2 === 0,
            2 => $c % 3 === 0,
            3 => ($r + $c) % 3 === 0,
            4 => (intdiv($r, 2) + intdiv($c, 3)) % 2 === 0,
            5 => ($r * $c) % 2 + ($r * $c) % 3 === 0,
            6 => (($r * $c) % 2 + ($r * $c) % 3) % 2 === 0,
            7 => (($r + $c) % 2 + ($r * $c) % 3) % 2 === 0,
            default => false,
        };
    }

    private function applyMask(array $mat, array $func, int $mask, int $size): array
    {
        $m = $mat;
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                // Nur Integer-Datenmodule maskieren (keine Funktionsmodule)
                if (!isset($func["$r,$c"]) && $m[$r][$c] !== self::FREE) {
                    if ($this->maskCondition($mask, $r, $c)) {
                        $m[$r][$c] ^= 1;
                    }
                }
            }
        }
        return $m;
    }

    private function selectMask(array $mat, array $func, int $size): int
    {
        $bestMask    = 0;
        $bestPenalty = PHP_INT_MAX;

        for ($mask = 0; $mask < 8; $mask++) {
            $candidate = $this->applyMask($mat, $func, $mask, $size);
            $pen       = $this->calculatePenalty($candidate, $size);
            if ($pen < $bestPenalty) {
                $bestPenalty = $pen;
                $bestMask    = $mask;
            }
        }

        return $bestMask;
    }

    private function calculatePenalty(array $mat, int $size): int
    {
        $penalty = 0;

        // Regel 1: 5+ aufeinanderfolgende gleiche Module
        for ($r = 0; $r < $size; $r++) {
            $penalty += $this->runPenalty(array_values($mat[$r]), $size);
        }
        for ($c = 0; $c < $size; $c++) {
            $col = array_column($mat, $c);
            $penalty += $this->runPenalty($col, $size);
        }

        // Regel 2: 2×2-Blöcke
        for ($r = 0; $r < $size - 1; $r++) {
            for ($c = 0; $c < $size - 1; $c++) {
                $v = $mat[$r][$c];
                if ($mat[$r][$c + 1] === $v && $mat[$r + 1][$c] === $v && $mat[$r + 1][$c + 1] === $v) {
                    $penalty += 3;
                }
            }
        }

        // Regel 4: Dunkles/helles Verhältnis
        $dark  = 0;
        $total = $size * $size;
        for ($r = 0; $r < $size; $r++) {
            $dark += array_sum($mat[$r]);
        }
        $pct     = $dark / $total * 100;
        $prev5   = (int)floor($pct / 5) * 5;
        $penalty += (int)(min(abs($prev5 - 50), abs($prev5 + 5 - 50)) / 5 * 10);

        return $penalty;
    }

    private function runPenalty(array $line, int $size): int
    {
        $penalty = 0;
        $count   = 1;
        for ($i = 1; $i < $size; $i++) {
            if ($line[$i] === $line[$i - 1]) {
                $count++;
                if ($count === 5)      $penalty += 3;
                elseif ($count > 5)    $penalty++;
            } else {
                $count = 1;
            }
        }
        return $penalty;
    }

    // ─── Format-Info schreiben ────────────────────────────────────────────────

    private function writeFormatInfo(array &$mat, string $ecc, int $mask, int $size): void
    {
        $bits = self::FMT_INFO[$ecc][$mask];

        // Kopie 1 – 15 feste Positionen nach ISO 18004
        $copy1 = [
            [8,0],[8,1],[8,2],[8,3],[8,4],[8,5],[8,7],[8,8],
            [7,8],[5,8],[4,8],[3,8],[2,8],[1,8],[0,8],
        ];
        foreach ($copy1 as $i => [$r, $c]) {
            $mat[$r][$c] = (int)$bits[$i];
        }

        // Kopie 2 – Zeile 8 von rechts + Spalte 8 von unten
        $copy2 = [];
        for ($c = $size - 1; $c >= $size - 8; $c--) {
            $copy2[] = [8, $c];
        }
        for ($r = $size - 7; $r < $size; $r++) {
            $copy2[] = [$r, 8];
        }
        foreach ($copy2 as $i => [$r, $c]) {
            if ($i < 15) {
                $mat[$r][$c] = (int)$bits[$i];
            }
        }
    }

    // ─── PNG-Renderer ─────────────────────────────────────────────────────────

    private function renderPng(array $matrixData, int $scale, int $margin): string
    {
        if (!extension_loaded('gd')) {
            throw new \RuntimeException('PHP-Extension ext-gd ist nicht verfügbar.');
        }

        $matrix  = $matrixData['matrix'];
        $modules = $matrixData['size'];
        $imgSize = ($modules + 2 * $margin) * $scale;

        $image = imagecreate($imgSize, $imgSize);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $white);

        for ($r = 0; $r < $modules; $r++) {
            for ($c = 0; $c < $modules; $c++) {
                if (($matrix[$r][$c] ?? 0) === 1) {
                    $x1 = ($c + $margin) * $scale;
                    $y1 = ($r + $margin) * $scale;
                    imagefilledrectangle($image, $x1, $y1, $x1 + $scale - 1, $y1 + $scale - 1, $black);
                }
            }
        }

        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        return $png;
    }

    // ─── SVG-Renderer ─────────────────────────────────────────────────────────

    private function renderSvg(array $matrixData, int $margin): string
    {
        $matrix  = $matrixData['matrix'];
        $modules = $matrixData['size'];
        $total   = $modules + 2 * $margin;

        $svg  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $svg .= sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 %1$d %1$d" '
            . 'width="%1$d" height="%1$d" shape-rendering="crispEdges">' . "\n",
            $total
        );
        $svg .= sprintf('<rect width="%d" height="%d" fill="#ffffff"/>' . "\n", $total, $total);

        $pathData = '';
        for ($r = 0; $r < $modules; $r++) {
            for ($c = 0; $c < $modules; $c++) {
                if (($matrix[$r][$c] ?? 0) === 1) {
                    $x = $c + $margin;
                    $y = $r + $margin;
                    $pathData .= "M$x,{$y}h1v1h-1Z ";
                }
            }
        }

        if ($pathData !== '') {
            $svg .= '<path fill="#000000" d="' . rtrim($pathData) . '"/>' . "\n";
        }

        $svg .= '</svg>';
        return $svg;
    }

    // ─── Hilfsmethoden ────────────────────────────────────────────────────────

    private function validateEcc(string $ecc): void
    {
        if (!in_array($ecc, [self::ECC_L, self::ECC_M, self::ECC_Q, self::ECC_H], true)) {
            throw new \InvalidArgumentException(
                "Ungültiger ECC-Level '$ecc'. Erlaubt: L, M, Q, H."
            );
        }
    }
}
