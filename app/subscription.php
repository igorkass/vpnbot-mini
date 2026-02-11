<?php
/*
    $vless - шорт-ссылка на конфиг vless
    $singbox - ссылка на конфиг singbox
    $clash - ссылка на конфиг mihomo
    $xray - ссылка на конфиг xray
    $windows - ссылка на архив скриптов singbox под Windows
    $download - входящий трафик
    $upload - исходящий трафик
    $uid - id пользователя
    $email - имя пользователя
    $configs['singbox'] - содержимое конфига singbox
    $configs['xray'] - содержимое конфига xray
    $configs['clash'] - содержимое конфига clash

    sing-box://import-remote-profile?url=SUB_URL#NAME
    hiddify://import/?url=SUB_URL
    v2raytun://import/SUB_URL
    happ://add/{SUB_URL}
    clash://install-config?url=SUB_URL

    SUB_URL - $vless | $singbox | $xray | $clash
*/

// Fetch server location information to get country code for VPN name
$serverCountry = 'N/A';
$vpnName = 'VPN';
$serverIP = '';

$context = stream_context_create(['http' => ['timeout' => 5]]);
$response = @file_get_contents('http://ip-api.com/json/?fields=country,countryCode,city,isp,query', false, $context);
if ($response !== false) {
    $data = json_decode($response, true);
    if ($data) {
        $serverCountry = $data['country'] ?? 'N/A';
        $cc = $data['countryCode'] ?? '';
        if ($cc) $vpnName = 'VPN-' . $cc;
        $serverIP = $data['query'] ?? '';
    }
}

// Prepare import URLs
$imports = [
    'singbox' => "sing-box://import-remote-profile?url=" . urlencode($singbox) . "#" . urlencode($vpnName),
    'hiddify' => "hiddify://import/{$singbox}",
    'hiddifyVless' => "hiddify://import/{$vless}",
    'v2raytun' => "v2raytun://import/{$xray}",
    'v2raytunVless' => "v2raytun://import/{$vless}",
    'happ' => "happ://add/{$xray}",
    'happVless' => "happ://add/{$vless}",
    'clash' => "clash://install-config?url=" . urlencode($clash),
];

// App definitions
$apps = [
    'android' => [
        ['name' => 'Sing-Box', 'instruction' => 'Рекомендуемый клиент', 'engine' => 'sing-box', 'sub' => $imports['singbox'], 'store' => 'https://play.google.com/store/apps/details?id=io.nekohasekai.sfa'],
        ['name' => 'Hiddify', 'engine' => 'sing-box', 'sub' => $imports['hiddify'], 'vless' => $imports['hiddifyVless'], 'store' => 'https://play.google.com/store/apps/details?id=app.hiddify.com'],
        ['name' => 'v2RayTun', 'engine' => 'X-Ray', 'sub' => $imports['v2raytun'], 'vless' => $imports['v2raytunVless'], 'store' => 'https://play.google.com/store/apps/details?id=com.v2raytun.android'],
        ['name' => 'Happ', 'engine' => 'X-Ray', 'sub' => $imports['happ'], 'vless' => $imports['happVless'], 'store' => 'https://play.google.com/store/apps/details?id=com.happproxy'],
    ],
    'ios' => [
        ['name' => 'Sing-Box', 'instruction' => 'Рекомендуемый клиент', 'engine' => 'sing-box', 'sub' => $imports['singbox'], 'store' => 'https://apps.apple.com/ru/app/sing-box-vt/id6673731168'],
        ['name' => 'v2RayTun', 'engine' => 'X-Ray', 'sub' => $imports['v2raytun'], 'vless' => $imports['v2raytunVless'], 'store' => 'https://apps.apple.com/ru/app/v2raytun/id6476628951'],
        ['name' => 'Happ', 'engine' => 'X-Ray', 'sub' => $imports['happ'], 'vless' => $imports['happVless'], 'store' => 'https://apps.apple.com/ru/app/happ-proxy-utility-plus/id6746188973'],
        ['name' => 'Clash Mi', 'engine' => 'Mihomo', 'sub' => $imports['clash'], 'store' => 'https://apps.apple.com/ru/app/clash-mi/id6744321968'],
    ],
    'macos' => [
        ['name' => 'Sing-Box', 'engine' => 'sing-box', 'sub' => $imports['singbox'], 'store' => 'https://apps.apple.com/ru/app/sing-box-vt/id6673731168'],
        ['name' => 'v2RayTun', 'engine' => 'X-Ray', 'sub' => $imports['v2raytun'], 'vless' => $imports['v2raytunVless'], 'store' => 'https://apps.apple.com/ru/app/v2raytun/id6476628951'],
        ['name' => 'Happ', 'engine' => 'X-Ray', 'sub' => $imports['happ'], 'vless' => $imports['happVless'], 'store' => 'https://apps.apple.com/ru/app/happ-proxy-utility-plus/id6746188973', 'note' => 'Требуется M1+'],
    ],
    'windows' => [
        ['name' => 'Автоустановка', 'engine' => 'sing-box', 'download' => $windows, 'instruction' => 'Распакуйте архив и запустите <strong>install.cmd</strong> от администратора для установки системного сервиса. Управление: <strong>start.cmd</strong>, <strong>stop.cmd</strong>, <strong>restart.cmd</strong>'],
    ],
];

// Available platforms
$platforms = [
    'android' => ['icon' => '🤖', 'label' => 'Android'],
    'ios' => ['icon' => '🍎', 'label' => 'iOS'],
    'macos' => ['icon' => '💻', 'label' => 'macOS'],
    'windows' => ['icon' => '🪟', 'label' => 'Windows'],
];

// Manual configurations
$manualConfigs = [
    ['name' => 'Sing-Box', 'url' => $singbox, 'hasDownload' => true],
    ['name' => 'X-Ray', 'url' => $xray, 'hasDownload' => true],
    ['name' => 'Mihomo', 'url' => $clash, 'hasDownload' => true],
    ['name' => 'VLESS (Короткая ссылка)', 'url' => $vless, 'hasDownload' => false],
];

// Helper function to render app block
function renderAppBlock($app)
{
    ?>
    <div class="app-card-mini">
        <div class="app-header-row">
            <span class="app-name"><?= htmlspecialchars($app['name']) ?></span>
            <?php if (isset($app['engine'])): ?>
                <div class="app-tags">
                    <span class="badge-pill"><?= htmlspecialchars($app['engine']) ?></span>
                    <?php if (isset($app['note'])): ?>
                        <span class="badge-pill badge-note"><?= htmlspecialchars($app['note']) ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (isset($app['instruction'])): ?>
            <div class="app-instruction">💡 <?= $app['instruction'] ?></div>
        <?php endif; ?>

        <div class="app-actions-stack">
            <?php if (isset($app['sub'])): ?>
                <div class="btn-item">
                    <a href="<?= htmlspecialchars($app['sub']) ?>" class="btn btn-primary">⚡ Подписка</a>
                    <button onclick="showQR('<?= htmlspecialchars($app['sub'], ENT_QUOTES) ?>')"
                        class="btn btn-secondary btn-qr">🔳 QR</button>
                </div>
            <?php endif; ?>

            <?php if (isset($app['vless'])): ?>
                <div class="btn-item">
                    <a href="<?= htmlspecialchars($app['vless']) ?>" class="btn btn-primary">🔗 VLESS</a>
                    <button onclick="showQR('<?= htmlspecialchars($app['vless'], ENT_QUOTES) ?>')"
                        class="btn btn-secondary btn-qr">🔳 QR</button>
                </div>
            <?php endif; ?>

            <?php if (isset($app['download'])): ?>
                <div class="btn-item">
                    <a href="<?= htmlspecialchars($app['download']) ?>" class="btn btn-install" download>📥 Скачать</a>
                </div>
            <?php endif; ?>

            <?php if (isset($app['store'])): ?>
                <div class="btn-item">
                    <a href="<?= htmlspecialchars($app['store']) ?>" target="_blank" rel="noopener" class="btn btn-install">📥
                        Установить</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

header('Content-type: text/html');
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки VPN</title>
    <style>
        :root {
            --primary: #818cf8;
            --primary-dark: #6366f1;
            --accent: #4f46e5;
            --bg-light: #2d3748;
            --bg-card: rgba(26, 32, 44, 0.9);
            --text: #edf2f7;
            --text-muted: #a0aec0;
            --radius: 14px;
            --radius-sm: 8px;
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            --transition: 0.22s cubic-bezier(.4, 0, .2, 1);
        }

        /* QR Modal */
        #qrModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeInModal 0.2s ease;
        }

        .qr-card {
            background: #2d3748;
            padding: 24px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            max-width: 320px;
            width: 100%;
        }

        .qr-card img,
        .qr-card canvas {
            width: 220px;
            height: 220px;
            margin-bottom: 16px;
            border-radius: 8px;
        }

        @keyframes fadeInModal {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #1a202c;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 24px 16px;
        }

        .container {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            max-width: 420px;
            width: 100%;
            padding: 32px 28px;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-info {
            background: var(--bg-light);
            border-radius: var(--radius-sm);
            padding: 16px 18px;
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            border: 1px solid #4a5568;
        }

        .header-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .header-info-label {
            color: var(--text-muted);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .header-info-value {
            color: var(--text);
            font-weight: 600;
            font-size: 1rem;
        }

        .header-info-value.loading {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .section {
            margin-bottom: 24px;
        }

        .section-title {
            color: var(--text);
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Accordion (details/summary) */
        details {
            margin-bottom: 10px;
            border-radius: var(--radius-sm);
            overflow: hidden;
        }

        summary {
            cursor: pointer;
            padding: 12px 14px;
            background: var(--bg-light);
            font-weight: 500;
            color: var(--text);
            list-style: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background var(--transition);
            user-select: none;
        }

        summary::-webkit-details-marker {
            display: none;
        }

        summary::after {
            content: '+';
            margin-left: auto;
            font-size: 1.2rem;
            color: var(--text-muted);
            transition: transform var(--transition);
        }

        details[open] summary::after {
            content: '−';
        }

        summary:hover {
            background: #4a5568;
        }

        .details-content {
            padding: 16px 12px;
        }

        .app-card-mini {
            background: rgba(45, 55, 72, 0.6);
            border: 1px solid rgba(74, 85, 104, 0.5);
            border-radius: var(--radius-sm);
            padding: 14px;
            margin-bottom: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .app-card-mini:last-child {
            margin-bottom: 0;
        }

        .app-header-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 12px;
            gap: 8px;
        }

        .app-tags {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .app-card-mini .app-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
        }

        .app-instruction {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 12px;
            padding: 8px 10px;
            background: rgba(180, 83, 9, 0.1);
            border-left: 3px solid #d97706;
            border-radius: 4px;
            line-height: 1.5;
            text-align: justify;
            width: 100%;
        }

        .app-instruction strong {
            color: var(--text);
            font-weight: 700;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            background: rgba(255, 255, 255, 0.1);
            padding: 1px 3px;
            border-radius: 3px;
            font-size: 0.73rem;
            white-space: nowrap;
        }

        .badge-pill {
            font-size: 0.62rem;
            color: var(--text-muted);
            background: #4a5568;
            padding: 3px 8px;
            border: 1px solid #718096;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
            white-space: nowrap;
            line-height: normal;
            display: inline-block;
        }

        .badge-note {
            background: rgba(155, 44, 44, 0.2);
            border-color: #e53e3e;
            color: #feb2b2;
            text-transform: none;
            letter-spacing: normal;
        }

        .btn-install {
            background: #10b981;
            color: #fff;
        }

        .btn-install:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .app-actions-stack {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-item {
            display: flex;
            gap: 4px;
        }

        .btn {
            flex: 1;
            min-width: 0;
            padding: 6px 10px;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 32px;
        }

        .btn-secondary {
            background: #4a5568;
            color: var(--text);
        }

        .btn-secondary:hover {
            background: #718096;
        }

        .btn.btn-qr {
            flex: 0 0 68px;
            min-width: 68px;
            padding: 0 2px;
            font-size: 0.75rem;
            border: 1px solid #718096;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* Manual settings grid */
        .app-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 12px;
        }

        .app-card {
            background: var(--bg-light);
            border-radius: var(--radius-sm);
            padding: 14px;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .app-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
        }

        .app-card .app-name {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .app-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .app-buttons .btn {
            flex: 1 1 0;
            min-width: 0;
        }

        .app-buttons .btn.btn-qr {
            flex: 0 0 68px;
            min-width: 68px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 10px;
        }

        .info-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 12px;
            background: var(--bg-light);
            border-radius: var(--radius-sm);
            text-align: center;
        }

        .info-label {
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .info-value {
            color: var(--text);
            font-size: 0.85rem;
            font-weight: 600;
            word-break: break-all;
        }

        .info-value.loading {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--text);
            color: #1a202c;
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            z-index: 1000;
            animation: slideIn 0.3s ease forwards;
        }

        .toast.hide {
            animation: slideOut 0.3s ease forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .container {
                padding: 20px 16px;
            }

            .btn {
                min-width: 100px;
                padding: 10px 10px;
                font-size: 0.82rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <section class="section">
            <h1 class="section-title">
                <span>🌐 Подключение</span>
                <span id="vpnStatus"></span>
            </h1>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">IP Адрес</div>
                    <div class="info-value" id="ipAddress">...</div>
                </div>
                <div class="info-card">
                    <div class="info-label">Страна</div>
                    <div class="info-value" id="country">...</div>
                </div>
                <div class="info-card">
                    <div class="info-label">Город</div>
                    <div class="info-value" id="city">...</div>
                </div>
                <div class="info-card">
                    <div class="info-label">Провайдер</div>
                    <div class="info-value" id="isp">...</div>
                </div>
            </div>
        </section>

        <section class="section">
            <h1 class="section-title">👤 Профиль</h1>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">Пользователь</div>
                    <div class="info-value"><?= htmlspecialchars($email) ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">Страна сервера</div>
                    <div class="info-value"><?= htmlspecialchars($serverCountry) ?></div>
                </div>
            </div>
        </section>

        <section class="section">
            <h1 class="section-title">🔐 Приложения и настройки</h1>

            <?php foreach ($platforms as $key => $platform): ?>
                <details data-platform="<?= $key ?>">
                    <summary><?= $platform['icon'] ?>     <?= $platform['label'] ?></summary>
                    <div class="details-content">
                        <?php foreach ($apps[$key] as $app): ?>
                            <?php renderAppBlock($app); ?>
                        <?php endforeach; ?>
                    </div>
                </details>
            <?php endforeach; ?>

            <details data-platform="manual">
                <summary>🛠 Ручные настройки</summary>
                <div class="details-content">
                    <div class="app-grid">
                        <?php foreach ($manualConfigs as $config): ?>
                            <div class="app-card">
                                <div class="app-name"><?= htmlspecialchars($config['name']) ?></div>
                                <div class="app-buttons">
                                    <?php if ($config['hasDownload']): ?>
                                        <a href="<?= htmlspecialchars($config['url']) ?>" class="btn btn-primary" download>📥
                                            Файл</a>
                                    <?php endif; ?>
                                    <button class="btn btn-secondary"
                                        onclick="copyToClipboard('<?= htmlspecialchars($config['url'], ENT_QUOTES) ?>')">📋
                                        Буфер</button>
                                    <button class="btn btn-secondary btn-qr"
                                        onclick="showQR('<?= htmlspecialchars($config['url'], ENT_QUOTES) ?>')">🔳
                                        QR</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </details>
        </section>
    </div>

    <div id="qrModal" onclick="this.style.display='none'">
        <div class="qr-card" onclick="event.stopPropagation()">
            <div id="qrContainer" style="display: flex; justify-content: center; margin-bottom: 16px;"></div>
            <button class="btn btn-primary" style="width:100%"
                onclick="document.getElementById('qrModal').style.display='none'">Закрыть</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function copyToClipboard(text) {
            if (navigator.clipboard?.writeText) {
                navigator.clipboard.writeText(text)
                    .then(() => showToast('✅ Скопировано!'))
                    .catch(() => fallbackCopy(text));
            } else {
                fallbackCopy(text);
            }
        }

        function fallbackCopy(text) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.cssText = 'position:fixed;left:-9999px';
            document.body.appendChild(ta);
            ta.select();
            try {
                document.execCommand('copy');
                showToast('✅ Скопировано!');
            } catch {
                showToast('❌ Ошибка копирования');
            }
            document.body.removeChild(ta);
        }

        function showToast(msg) {
            const t = document.createElement('div');
            t.className = 'toast';
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(() => {
                t.classList.add('hide');
                setTimeout(() => t.remove(), 300);
            }, 2000);
        }

        function showQR(data) {
            const modal = document.getElementById('qrModal');
            const container = document.getElementById('qrContainer');

            // Delete previous QR code
            container.innerHTML = '';

            // Generate a new QR code
            new QRCode(container, {
                text: data,
                width: 220,
                height: 220,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });

            modal.style.display = 'flex';
        }

        // Accordion: only one details open at a time
        document.querySelectorAll('details').forEach(d => {
            d.addEventListener('toggle', () => {
                if (d.open) {
                    document.querySelectorAll('details').forEach(other => {
                        if (other !== d) other.open = false;
                    });
                }
            });
        });

        // Auto-expand section based on user's OS
        (function () {
            const userAgent = navigator.userAgent.toLowerCase();
            let platform = null;

            if (/android/.test(userAgent)) {
                platform = 'android';
            } else if (/iphone|ipad|ipod/.test(userAgent)) {
                platform = 'ios';
            } else if (/mac os x/.test(userAgent)) {
                platform = 'macos';
            } else if (/windows/.test(userAgent)) {
                platform = 'windows';
            }

            if (platform) {
                const section = document.querySelector(`details[data-platform="${platform}"]`);
                if (section) {
                    section.open = true;
                }
            }
        })();

        // Fetch client IP information with multiple fallback APIs
        const vpnServerIP = "<?= htmlspecialchars($serverIP) ?>";

        function fetchWithTimeout(url, ms) {
            return new Promise((resolve, reject) => {
                const ctrl = new AbortController();
                const timer = setTimeout(() => ctrl.abort(), ms);
                fetch(url, { signal: ctrl.signal })
                    .then(r => { clearTimeout(timer); resolve(r); })
                    .catch(e => { clearTimeout(timer); reject(e); });
            });
        }

        async function fetchClientGeo() {
            const apis = [
                {
                    url: 'https://get.geojs.io/v1/ip/geo.json',
                    map: d => ({ ip: d.ip, country: d.country, city: d.city, isp: d.organization_name || '' })
                },
                {
                    url: 'https://api.db-ip.com/v2/free/self',
                    map: d => ({ ip: d.ipAddress, country: d.countryName, city: d.city, isp: '' })
                },
            ];
            for (const api of apis) {
                try {
                    const res = await fetchWithTimeout(api.url, 6000);
                    if (!res.ok) continue;
                    const raw = await res.json();
                    const data = api.map(raw);
                    if (data.ip) return data;
                } catch(e) { continue; }
            }
            return null;
        }

        fetchClientGeo().then(data => {
            if (data) {
                document.getElementById('ipAddress').textContent = data.ip || 'N/A';
                document.getElementById('country').textContent = data.country || 'N/A';
                document.getElementById('city').textContent = data.city || 'N/A';
                document.getElementById('isp').textContent = data.isp || '—';

                const statusEl = document.getElementById('vpnStatus');
                if (vpnServerIP && data.ip) {
                    if (data.ip === vpnServerIP) {
                        statusEl.innerHTML = '<span style="color:#10b981">✅ Защищено</span>';
                    } else {
                        statusEl.innerHTML = '<span style="color:#e53e3e">❌ Не защищено</span>';
                    }
                }
            } else {
                ['ipAddress','country','city','isp'].forEach(id => {
                    document.getElementById(id).textContent = 'Ошибка';
                });
            }
        });
    </script>
</body>

</html>