@extends('layouts.app')

@section('title', 'Scanner · ' . $event->name)

@section('content')
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Scanner</div>
                <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 12px; letter-spacing: -0.5px;">
                    {{ $event->name }}
                </h1>
                <div class="muted" style="font-size: 16px;">
                    {{ $event->starts_at->format('d/m/Y H:i') }} · {{ $event->venue_name }}
                </div>
            </div>
            <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center;">
                <div class="card" style="padding: 20px; min-width: 160px;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                        Présents
                    </div>
                    <div style="display: flex; align-items: baseline; gap: 8px;">
                        <span id="presentCount" style="font-weight: 900; font-size: 32px; color: var(--we-primary);">
                            {{ $presentCount }}
                        </span>
                        <span class="muted" style="font-size: 18px;">/ {{ $event->capacity }}</span>
                    </div>
                    <div style="font-size: 12px; color: var(--we-muted); margin-top: 4px;">
                        @php($percentage = $event->capacity > 0 ? round(($presentCount / $event->capacity) * 100) : 0)
                        {{ $percentage }}% de capacité
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid2" style="gap: 32px; margin-bottom: 32px;">
        <!-- Zone de scan -->
        <div style="flex: 1;">
            <!-- Scanner QR Code -->
            <div class="card" style="padding: 32px; margin-bottom: 24px;">
                <h2 style="font-size: 24px; font-weight: 900; margin-bottom: 24px; letter-spacing: -0.3px;">📱 Scanner QR Code</h2>
                
                <!-- Zone du scanner -->
                <div id="scannerSection" style="margin-bottom: 24px;">
                    <div id="cameraContainer" style="display: none;">
                        <div style="position: relative; width: 100%; max-width: 500px; margin: 0 auto; border-radius: 16px; overflow: hidden; background: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                            <div id="qr-reader" style="width: 100%; position: relative;"></div>
                        </div>
                        <div style="text-align: center; margin-top: 20px;">
                            <div style="font-size: 14px; color: var(--we-text); font-weight: 600; margin-bottom: 8px;">
                                Pointez la caméra vers le QR code du billet
                            </div>
                            <button id="stopCameraBtn" class="btn secondary" type="button" style="padding: 12px 24px; font-size: 14px;">
                                ⏹️ Arrêter le scanner
                            </button>
                        </div>
                    </div>

                    <div id="scannerPlaceholder" style="padding: 60px 40px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border-radius: 16px; border: 2px dashed rgba(234, 88, 12, 0.3); text-align: center;">
                        <div style="font-size: 64px; margin-bottom: 16px;">📷</div>
                        <div style="font-size: 20px; font-weight: 700; color: var(--we-text); margin-bottom: 12px;">
                            Scanner un billet
                        </div>
                        <div style="font-size: 14px; color: var(--we-muted); margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">
                            Activez votre caméra pour scanner automatiquement les QR codes des billets
                        </div>
                        <button id="startScannerBtn" class="btn" type="button" style="padding: 14px 28px; font-size: 16px;">
                            ▶️ Démarrer le scanner
                        </button>
                    </div>
                </div>

                <!-- Saisie manuelle (optionnel) -->
                <div style="padding-top: 24px; border-top: 2px solid var(--we-border);">
                    <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; text-align: center;">
                        Ou saisie manuelle
                    </div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
                        <div style="flex: 1; min-width: 200px;">
                            <input id="qr_token" 
                                placeholder="Collez le token QR ici…" 
                                autocomplete="off"
                                style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 2px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px; font-family: ui-monospace, monospace;"
                                onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                        <div>
                            <button class="btn" id="scanBtn" type="button" style="padding: 14px 24px; font-size: 14px;">
                                Scanner
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pop-up de résultat du scan -->
            <div id="resultModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1000; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
                <div class="card" style="max-width: 500px; width: 100%; padding: 32px; position: relative; animation: slideIn 0.3s ease-out;">
                    <button id="closeModalBtn" style="position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 24px; color: #8b7355; cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: background 120ms ease;" 
                            onmouseover="this.style.background='rgba(0,0,0,0.05)'" 
                            onmouseout="this.style.background='none'">
                        ×
                    </button>
                    <div id="resultContent"></div>
                </div>
            </div>

            <style>
                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateY(-20px) scale(0.95);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }
            </style>
        </div>

        <!-- Statistiques -->
        <div style="flex: 0 0 380px;">
            <div class="card" style="padding: 32px;">
                <h3 style="font-size: 20px; font-weight: 900; margin-bottom: 24px; letter-spacing: -0.3px;">Statistiques</h3>
                
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Taux de remplissage
                        </div>
                        <div style="height: 12px; background: #f1f5f9; border-radius: 999px; overflow: hidden;">
                            <div id="capacityBar" style="height: 100%; background: linear-gradient(90deg, var(--we-primary), rgba(245, 130, 32, 0.8)); border-radius: 999px; transition: width 0.3s ease; width: {{ $event->capacity > 0 ? ($presentCount / $event->capacity) * 100 : 0 }}%;"></div>
                        </div>
                        <div style="font-size: 14px; color: var(--we-text); margin-top: 8px;">
                            <span id="presentCountStat">{{ $presentCount }}</span> personnes présentes
                        </div>
                    </div>

                    <div style="padding-top: 20px; border-top: 2px solid var(--we-border);">
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Places restantes
                        </div>
                        <div style="font-size: 28px; font-weight: 900; color: var(--we-text);">
                            <span id="remainingCount">{{ max(0, $event->capacity - $presentCount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bibliothèque HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <style>
        /* Styles pour l'aperçu vidéo du scanner */
        #qr-reader {
            position: relative;
            width: 100%;
            min-height: 400px;
        }
        
        #qr-reader video {
            width: 100% !important;
            height: auto !important;
            display: block;
            border-radius: 16px;
            object-fit: cover;
        }
        
        #qr-reader__dashboard {
            display: none !important;
        }
        
        #qr-reader__scan_region {
            position: relative;
        }
        
        #qr-reader__camera_selection {
            display: none;
        }
        
        /* Overlay personnalisé pour le guide de scan */
        #qr-reader__scan_region::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border: 3px solid var(--we-primary);
            border-radius: 12px;
            pointer-events: none;
            z-index: 10;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.4);
        }
    </style>

    <script>
        const btn = document.getElementById('scanBtn');
        const input = document.getElementById('qr_token');
        const modal = document.getElementById('resultModal');
        const resultContent = document.getElementById('resultContent');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const presentCount = document.getElementById('presentCount');
        const presentCountStat = document.getElementById('presentCountStat');
        const remainingCount = document.getElementById('remainingCount');
        const capacityBar = document.getElementById('capacityBar');
        const totalCapacity = {{ $event->capacity }};
        const startScannerBtn = document.getElementById('startScannerBtn');
        const stopCameraBtn = document.getElementById('stopCameraBtn');
        const cameraContainer = document.getElementById('cameraContainer');
        const scannerPlaceholder = document.getElementById('scannerPlaceholder');
        let html5QrCode = null;
        let isScanning = false;

        function updateStats(newCount) {
            presentCount.textContent = newCount;
            presentCountStat.textContent = newCount;
            remainingCount.textContent = Math.max(0, totalCapacity - newCount);
            const percentage = totalCapacity > 0 ? (newCount / totalCapacity) * 100 : 0;
            capacityBar.style.width = percentage + '%';
        }

        function showResult(result, message, extra = {}) {
            modal.style.display = 'flex';
            
            const configs = {
                valid: {
                    icon: '✅',
                    title: 'Billet valide',
                    bg: 'rgba(34, 197, 94, 0.1)',
                    border: 'rgba(34, 197, 94, 0.3)',
                    titleColor: '#16a34a',
                    messageColor: '#15803d'
                },
                already_used: {
                    icon: '⚠️',
                    title: 'Billet déjà scanné',
                    bg: 'rgba(251, 191, 36, 0.1)',
                    border: 'rgba(251, 191, 36, 0.3)',
                    titleColor: '#d97706',
                    messageColor: '#b45309'
                },
                invalid: {
                    icon: '❌',
                    title: 'Billet invalide',
                    bg: 'rgba(239, 68, 68, 0.1)',
                    border: 'rgba(239, 68, 68, 0.3)',
                    titleColor: '#dc2626',
                    messageColor: '#b91c1c'
                }
            };

            const config = configs[result] || configs.invalid;

            let html = `
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid var(--we-border);">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: ${config.bg}; display: flex; align-items: center; justify-content: center; font-size: 32px; flex-shrink: 0;">
                        ${config.icon}
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 24px; font-weight: 900; color: ${config.titleColor}; margin-bottom: 4px;">
                            ${config.title}
                        </div>
                        <div style="font-size: 16px; color: ${config.messageColor}; font-weight: 600;">
                            ${message || ''}
                        </div>
                    </div>
                </div>
            `;

            if (extra.attendee) {
                html += `
                    <div style="padding: 16px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border); margin-bottom: 12px;">
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Participant
                        </div>
                        <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 4px;">
                            ${extra.attendee.name}
                        </div>
                        <div style="font-size: 14px; color: var(--we-muted);">
                            ${extra.attendee.email}
                        </div>
                    </div>
                `;
            }

            if (extra.checked_in_at) {
                html += `
                    <div style="padding: 16px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border);">
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                            Premier scan
                        </div>
                        <div style="font-size: 14px; color: var(--we-text);">
                            ${new Date(extra.checked_in_at).toLocaleString('fr-FR', { 
                                day: '2-digit', 
                                month: '2-digit', 
                                year: 'numeric', 
                                hour: '2-digit', 
                                minute: '2-digit',
                                second: '2-digit'
                            })}
                        </div>
                    </div>
                `;
            }

            resultContent.innerHTML = html;
            input.value = '';
            
            // Ne pas remettre le focus sur l'input si le scanner est actif
            if (!isScanning) {
                input.focus();
            }
        }

        // Fermer la modale
        function closeModal() {
            modal.style.display = 'none';
            
            // Reprendre le scanner si actif
            if (html5QrCode && isScanning) {
                try {
                    html5QrCode.resume();
                } catch (e) {
                    // Si resume échoue, ignorer
                }
            }
            
            if (!isScanning) {
                input.focus();
            }
        }

        closeModalBtn.addEventListener('click', closeModal);
        
        // Fermer en cliquant sur l'overlay
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });

        async function doScan() {
            const token = input.value.trim();
            if (!token) {
                input.focus();
                return Promise.resolve();
            }

            btn.disabled = true;
            btn.textContent = 'Scan en cours...';
            
            try {
                const res = await fetch(@json(route('scanner.scan', $event)), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ qr_token: token }),
                });
                
                const data = await res.json().catch(() => ({}));
                
                showResult(data.result || 'invalid', data.message || 'Erreur', data);
                
                // Mettre à jour les statistiques si le scan est valide
                if (data.result === 'valid' && typeof data.present_count !== 'undefined') {
                    updateStats(data.present_count);
                }
            } catch (e) {
                showResult('invalid', 'Erreur réseau. Veuillez réessayer.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Scanner';
            }
        }

        btn.addEventListener('click', doScan);
        
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                doScan();
            }
        });

        // Scan automatique si token présent dans l'URL ou session
        @if(session('auto_scan_token'))
            const autoToken = @json(session('auto_scan_token'));
            if (autoToken) {
                input.value = autoToken;
                setTimeout(() => {
                    doScan();
                }, 500);
            }
        @endif

        // Vérifier aussi l'URL pour un token (si scanné depuis un autre appareil)
        const urlParams = new URLSearchParams(window.location.search);
        const urlToken = urlParams.get('token');
        if (urlToken && !input.value) {
            input.value = urlToken;
            setTimeout(() => {
                doScan();
            }, 500);
        }

        // Focus automatique sur le champ de saisie si pas de scan auto
        if (!input.value) {
            input.focus();
        }

        // Gestion du scanner QR Code
        async function startScanner() {
            try {
                startScannerBtn.disabled = true;
                startScannerBtn.textContent = '⏳ Activation...';
                
                html5QrCode = new Html5Qrcode("qr-reader");
                
                await html5QrCode.start(
                    { facingMode: "environment" }, // Utiliser la caméra arrière par défaut
                    {
                        fps: 10,
                        qrbox: function(viewfinderWidth, viewfinderHeight) {
                            // Zone de scan adaptative
                            let minEdgePercentage = 0.7;
                            let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                            let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                            return {
                                width: qrboxSize,
                                height: qrboxSize
                            };
                        },
                        aspectRatio: 1.0,
                        videoConstraints: {
                            facingMode: "environment"
                        },
                        rememberLastUsedCamera: true
                    },
                    (decodedText, decodedResult) => {
                        // QR code détecté - traiter automatiquement
                        handleScannedToken(decodedText);
                    },
                    (errorMessage) => {
                        // Erreur ignorée (scan en cours)
                    }
                );

                isScanning = true;
                cameraContainer.style.display = 'block';
                scannerPlaceholder.style.display = 'none';
            } catch (err) {
                alert('Impossible d\'accéder à la caméra. Veuillez vérifier les permissions de votre navigateur.');
                console.error(err);
                startScannerBtn.disabled = false;
                startScannerBtn.textContent = '▶️ Démarrer le scanner';
            }
        }

        async function stopScanner() {
            if (html5QrCode && isScanning) {
                try {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                    html5QrCode = null;
                    isScanning = false;
                    cameraContainer.style.display = 'none';
                    scannerPlaceholder.style.display = 'block';
                    startScannerBtn.disabled = false;
                    startScannerBtn.textContent = '▶️ Démarrer le scanner';
                } catch (err) {
                    console.error(err);
                }
            }
        }

        function handleScannedToken(token) {
            // Si le token est une URL, extraire le token
            let qrToken = token;
            
            // Si c'est une URL de scan, extraire le token
            const urlMatch = token.match(/\/scan\/([^\/\?]+)/);
            if (urlMatch) {
                qrToken = urlMatch[1];
            }
            
            // Si le scanner est actif, arrêter temporairement pour traiter
            if (html5QrCode && isScanning) {
                html5QrCode.pause();
            }
            
            // Scanner le token
            input.value = qrToken;
            doScan();
            // Le scanner reprendra automatiquement quand l'utilisateur ferme la pop-up
        }

        startScannerBtn.addEventListener('click', startScanner);
        stopCameraBtn.addEventListener('click', stopScanner);

        // Arrêter la caméra si on quitte la page
        window.addEventListener('beforeunload', () => {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().catch(() => {});
            }
        });
    </script>
@endsection
