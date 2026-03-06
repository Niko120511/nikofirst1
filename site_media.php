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
        'projects_1' => 'https://imgs.search.brave.com/hGagxjdNrU8qx3N9hp9wNsnDamG-PSRFS_BlMoCCwAE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4ubW9zLmNtcy5mdXR1cmVjZG4ubmV0L1pO/cktTZ3EyZlM5dGV6cUo3M282Q1MuanBn',
        'projects_2' => 'https://imgs.search.brave.com/X1CzBkUqU7E5QoCaIiqWcmgQrY_BeZ8rCJwhAtd8lZQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzAxLzc3LzYyLzA0/LzM2MF9GXzE3NzYy/MDQ4Ml8zZU9maHBR/VkhLM1lnenZVVG13/ZFZacmhrZ2plekFB/dC5qcGc'             
        'projects_3' => 'https://imgs.search.brave.com/DbOtamDM9nSXXql2LH76OvzGC3ek9ZW9asry1KJJsgI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRpYS5nZXR0eWltYWdlcy5jb20vaWQvOTU5MjQyMDI0L3Bob3Rv/L3RoZS1kcm9uZS1pbi1zdW5zZXQtc2t5LmpwZw',
        'projects_4' => 'https://imgs.search.brave.com/x16PAq-RYMtMS2IHkMAtErNTkNG6BTNu0nr0zvkc4eI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRpYS5pc3RvY2twaG90by5jb20vaWQvNTM3MjY5NDA0L3Bob3Rv/L21hbi1vcGVyYXRpbmctb2YtZmx5aW5nLWRyb25lLXF1YWRyb2NvcHRlci1hdC1zdW5zZXQuanBn',
        'gallery_1' => 'https://imgs.search.brave.com/oWuf9PObLkHDJyexud4QAcZxz6-mQi5A98jXp33jQmQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRpYS5nZXR0eWltYWdlcy5jb20vaWQvMjE5MjE1Nzc2My9waG90by9nbGFzdG9uYnVyeS11bml0ZWQta2luZ2RvbS1hLWRqaS1taW5pLTMtcHJvLWRyb25lLWZsaWVzLWFib3ZlLXRoZS1ncm91bmQtb24tamFudWFyeS0yLTIwMjUuanBn',
        'gallery_2' => 'https://imgs.search.brave.com/hGagxjdNrU8qx3N9hp9wNsnDamG-PSRFS_BlMoCCwAE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4ubW9zLmNtcy5mdXR1cmVjZG4ubmV0L1pOcktTZ3EyZlM5dGV6cUo3M282Q1MuanBn',
        'gallery_3' => 'https://imgs.search.brave.com/X1CzBkUqU7E5QoCaIiqWcmgQrY_BeZ8rCJwhAtd8lZQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzAxLzc3LzYyLzA0/LzM2MF9GXzE3NzYyMDQ4Ml8zZU9maHBRVkhLM1lnenZVVG13ZFZacmhrZ2plekFBdC5qcGc',
        'gallery_4' => 'https://imgs.search.brave.com/DbOtamDM9nSXXql2LH76OvzGC3ek9ZW9asry1KJJsgI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRpYS5nZXR0eWltYWdlcy5jb20vaWQvOTU5MjQyMDI0L3Bob3RvL3RoZS1kcm9uZS1pbi1zdW5zZXQtc2t5LmpwZw',
        'gallery_5' => 'https://imgs.search.brave.com/x16PAq-RYMtMS2IHkMAtErNTkNG6BTNu0nr0zvkc4eI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRpYS5pc3RvY2twaG90by5jb20vaWQvNTM3MjY5NDA0L3Bob3RvL21hbi1vcGVyYXRpbmctb2YtZmx5aW5nLWRyb25lLXF1YWRyb2NvcHRlci1hdC1zdW5zZXQuanBn',
        'gallery_6' => 'https://imgs.search.brave.com/oWuf9PObLkHDJyexud4QAcZxz6-mQi5A98jXp33jQmQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRpYS5nZXR0eWltYWdlcy5jb20vaWQvMjE5MjE1Nzc2My9waG90by9nbGFzdG9uYnVyeS11bml0ZWQta2luZ2RvbS1hLWRqaS1taW5pLTMtcHJvLWRyb25lLWZsaWVzLWFib3ZlLXRoZS1ncm91bmQtb24tamFudWFyeS0yLTIwMjUuanBn',
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
