<?php
declare(strict_types=1);

function getDefaultSiteMedia(): array
{
    return [
        'home_hero' => 'https://images.unsplash.com/photo-1473968512647-3e447244af8f?auto=format&fit=crop&w=1200&q=80',
        'home_highlight_1' => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=1000&q=80',
        'home_highlight_2' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1000&q=80',
        'home_highlight_3' => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=1000&q=80',
        'home_highlight_4' => 'https://images.unsplash.com/photo-1508444845599-5c89863b1c44?auto=format&fit=crop&w=1000&q=80',
        'projects_1' => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=1200&q=80',
        'projects_2' => 'https://images.unsplash.com/photo-1508444845599-5c89863b1c44?auto=format&fit=crop&w=1200&q=80',
        'projects_3' => 'https://images.unsplash.com/photo-1473968512647-3e447244af8f?auto=format&fit=crop&w=1200&q=80',
        'projects_4' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1200&q=80',
        'gallery_1' => 'https://images.unsplash.com/photo-1517976487492-576ea6b2936d?auto=format&fit=crop&w=1200&q=80',
        'gallery_2' => 'https://images.unsplash.com/photo-1473091534298-04dcbce3278c?auto=format&fit=crop&w=1200&q=80',
        'gallery_3' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1200&q=80',
        'gallery_4' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1200&q=80',
        'gallery_5' => 'https://images.unsplash.com/photo-1517423440428-a5a00ad493e8?auto=format&fit=crop&w=1200&q=80',
        'gallery_6' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80',
        'team_hero' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
    ];
}

function ensureSiteMediaTable(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS site_media (
            media_key VARCHAR(80) PRIMARY KEY,
            media_url TEXT NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
    );
}

function getSiteMediaMap(PDO $pdo): array
{
    ensureSiteMediaTable($pdo);

    $map = getDefaultSiteMedia();
    $rows = $pdo->query('SELECT media_key, media_url FROM site_media')->fetchAll();

    foreach ($rows as $row) {
        $key = (string)($row['media_key'] ?? '');
        $url = trim((string)($row['media_url'] ?? ''));
        if ($key !== '' && $url !== '') {
            $map[$key] = $url;
        }
    }

    return $map;
}
