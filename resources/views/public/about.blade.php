@extends('layouts.app')

@section('title', "À propos · Win's Events")

@section('content')
    <!-- Hero Section premium -->
    <div style="margin-bottom: 48px;">
        <div class="card" style="padding: 0; overflow: hidden; position: relative;">
            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 0; min-height: 500px;">
                <div style="padding: 64px; display: flex; flex-direction: column; justify-content: center; background: linear-gradient(135deg, rgba(234, 88, 12, 0.06) 0%, rgba(245, 130, 32, 0.02) 100%); position: relative;">
                    <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: linear-gradient(180deg, var(--we-primary), var(--we-primary-hover));"></div>
                    <div style="margin-bottom: 8px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">À propos de nous</div>
                    <h1 style="font-size: 48px; font-weight: 900; line-height: 1.1; margin-bottom: 24px; letter-spacing: -0.5px;">
                        Win's Events<br />
                        <span style="color: var(--we-primary);">Organisateur de soirées à succès</span>
                    </h1>
                    <p style="font-size: 18px; line-height: 1.7; color: var(--we-muted); margin-bottom: 32px; max-width: 560px;">
                        Depuis nos débuts, Win's Events conçoit et produit des soirées mémorables. Nous prenons en charge toute l'expérience — de la billetterie à l'accueil, en passant par la sécurité — pour que chaque soirée soit une réussite.
                    </p>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="{{ route('public.events.index') }}" class="btn">Découvrir nos soirées</a>
                        <a href="#valeurs" class="btn secondary">Notre engagement</a>
                    </div>
                </div>
                <div style="position: relative; background: #f5f5f5; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img id="hero-image" src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&q=80" alt="Soirée Win's Events" style="width: 100%; height: 100%; object-fit: cover;" />
                    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.1) 100%);"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Notre histoire -->
    <section style="margin-bottom: 48px;">
        <div class="card" style="padding: 48px;">
            <div style="max-width: 800px; margin: 0 auto; text-align: center;">
                <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Notre histoire</div>
                <h2 style="font-size: 36px; font-weight: 900; margin-bottom: 24px; letter-spacing: -0.5px;">Une passion pour créer des moments inoubliables</h2>
                <p style="font-size: 18px; line-height: 1.8; color: var(--we-muted); margin-bottom: 24px;">
                    Win's Events est né d'une vision simple : rendre l'organisation et la participation aux soirées exceptionnelles accessibles à tous. Nous combinons expertise technique, créativité et attention aux détails pour transformer chaque événement en une expérience mémorable.
                </p>
                <p style="font-size: 16px; line-height: 1.7; color: var(--we-muted);">
                    Notre plateforme innovante permet aux organisateurs de gérer facilement leurs événements, tandis que les participants bénéficient d'un processus de réservation fluide et sécurisé, de la commande à l'entrée en soirée.
                </p>
            </div>
        </div>
    </section>

    <!-- Valeurs / Avantages avec design premium -->
    <section id="valeurs" style="margin-bottom: 48px;">
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Notre engagement</div>
            <h2 style="font-size: 36px; font-weight: 900; margin-bottom: 16px; letter-spacing: -0.5px;">Ce qui nous distingue</h2>
            <p class="muted" style="font-size: 18px; max-width: 700px; margin: 0 auto;">
                Win's Events conçoit chaque soirée pour qu'elle marque les esprits. Nous combinons créativité, logistique irréprochable et souci du détail pour offrir des expériences qui rassemblent.
            </p>
        </div>

        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            <div class="card" style="padding: 32px; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(245, 130, 32, 0.05)); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 32px;">🎉</div>
                <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.3px;">Conception & production</h3>
                <p class="muted" style="line-height: 1.7; font-size: 15px;">
                    Du concept à la scène, nous orchestrons chaque étape pour créer des ambiances uniques et mémorables qui marquent les esprits.
                </p>
            </div>
            <div class="card" style="padding: 32px; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(245, 130, 32, 0.05)); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 32px;">🎫</div>
                <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.3px;">Billetterie & accueil</h3>
                <p class="muted" style="line-height: 1.7; font-size: 15px;">
                    Accueil optimisé et billetterie fluide pour une expérience invitée sans friction, de la réservation à l'entrée en toute simplicité.
                </p>
            </div>
            <div class="card" style="padding: 32px; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(245, 130, 32, 0.05)); display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 32px;">🛡️</div>
                <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.3px;">Sécurité & logistique</h3>
                <p class="muted" style="line-height: 1.7; font-size: 15px;">
                    Des procédures claires et des équipes formées pour garantir le bon déroulement et la sécurité de tous les participants.
                </p>
            </div>
        </div>
    </section>

    <!-- Statistiques avec design premium -->
    <section style="margin-bottom: 48px;">
        <div class="card" style="padding: 48px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.03) 0%, rgba(245, 130, 32, 0.01) 100%);">
            <div style="text-align: center; margin-bottom: 40px;">
                <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Nos résultats</div>
                <h3 style="font-size: 32px; font-weight: 900; letter-spacing: -0.5px;">Quelques chiffres qui parlent</h3>
            </div>
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 32px; text-align: center;">
                <div>
                    <div style="font-size: 56px; font-weight: 900; color: var(--we-primary); margin-bottom: 12px; line-height: 1;">+120</div>
                    <div class="muted" style="font-size: 15px; font-weight: 600;">Soirées organisées</div>
                </div>
                <div>
                    <div style="font-size: 56px; font-weight: 900; color: var(--we-primary); margin-bottom: 12px; line-height: 1;">+8k</div>
                    <div class="muted" style="font-size: 15px; font-weight: 600;">Billets vendus</div>
                </div>
                <div>
                    <div style="font-size: 56px; font-weight: 900; color: var(--we-primary); margin-bottom: 12px; line-height: 1;">4.9/5</div>
                    <div class="muted" style="font-size: 15px; font-weight: 600;">Satisfaction client</div>
                </div>
                <div>
                    <div style="font-size: 56px; font-weight: 900; color: var(--we-primary); margin-bottom: 12px; line-height: 1;">98%</div>
                    <div class="muted" style="font-size: 15px; font-weight: 600;">Taux de présence</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages avec design premium -->
    <section style="margin-bottom: 48px;">
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Témoignages</div>
            <h3 style="font-size: 32px; font-weight: 900; margin-bottom: 16px; letter-spacing: -0.5px;">Ils nous ont fait confiance</h3>
            <p class="muted" style="font-size: 18px;">Découvrez ce que nos clients et partenaires disent de Win's Events.</p>
        </div>

        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
            <div class="card" style="padding: 32px; position: relative;">
                <div style="position: absolute; top: 24px; right: 24px; font-size: 48px; opacity: 0.1;">"</div>
                <blockquote style="font-size: 17px; line-height: 1.7; margin-bottom: 24px; font-style: italic; color: #334155; position: relative; z-index: 1;">
                    "Win's Events a simplifié l'organisation de nos soirées — paiement et contrôle, tout marche parfaitement. Une plateforme vraiment professionnelle qui nous fait gagner un temps précieux."
                </blockquote>
                <div style="display: flex; align-items: center; gap: 16px; padding-top: 20px; border-top: 1px solid var(--we-border);">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, var(--we-primary), var(--we-primary-hover)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 20px; flex-shrink: 0;">J</div>
                    <div>
                        <div style="font-weight: 700; font-size: 16px; margin-bottom: 4px;">Julien</div>
                        <div class="muted" style="font-size: 14px;">Organisateur d'événements</div>
                    </div>
                </div>
            </div>
            <div class="card" style="padding: 32px; position: relative;">
                <div style="position: absolute; top: 24px; right: 24px; font-size: 48px; opacity: 0.1;">"</div>
                <blockquote style="font-size: 17px; line-height: 1.7; margin-bottom: 24px; font-style: italic; color: #334155; position: relative; z-index: 1;">
                    "Très simple d'utilisation, le support est réactif et fiable. J'ai réservé mes billets en quelques minutes et tout s'est passé à merveille le jour J. Une expérience vraiment fluide."
                </blockquote>
                <div style="display: flex; align-items: center; gap: 16px; padding-top: 20px; border-top: 1px solid var(--we-border);">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, var(--we-primary), var(--we-primary-hover)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 20px; flex-shrink: 0;">C</div>
                    <div>
                        <div style="font-weight: 700; font-size: 16px; margin-bottom: 4px;">Clara</div>
                        <div class="muted" style="font-size: 14px;">Participante régulière</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galerie d'images avec design premium -->
    <section style="margin-bottom: 48px;">
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Galerie</div>
            <h3 style="font-size: 32px; font-weight: 900; margin-bottom: 16px; letter-spacing: -0.5px;">Ambiance en images</h3>
            <p class="muted" style="font-size: 18px;">Quelques moments capturés lors de nos soirées exceptionnelles.</p>
        </div>

        <div id="gallery-container" class="grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            <!-- Les images seront chargées dynamiquement via JavaScript -->
        </div>
    </section>

    <!-- CTA Final premium -->
    <div class="card" style="text-align: center; padding: 64px 32px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.05) 0%, rgba(245, 130, 32, 0.02) 100%); position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 200px; height: 4px; background: linear-gradient(90deg, transparent, var(--we-primary), transparent);"></div>
        <h3 style="font-size: 40px; font-weight: 900; margin-bottom: 20px; letter-spacing: -0.5px;">Prêt à découvrir des soirées près de chez vous ?</h3>
        <p class="muted" style="font-size: 18px; margin-bottom: 32px; max-width: 650px; margin-left: auto; margin-right: auto; line-height: 1.7;">
            Rejoignez notre communauté et réservez votre place pour les prochaines soirées exceptionnelles. L'aventure commence ici.
        </p>
        <a href="{{ route('public.events.index') }}" class="btn" style="font-size: 17px; padding: 16px 32px;">Voir les soirées à venir</a>
    </div>

    <script>
        // Images par défaut
        const defaultImages = [
            { url: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&q=80', alt: 'Soirée dansante avec lumières' },
            { url: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80', alt: 'Public en soirée sur la piste' },
            { url: 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=800&q=80', alt: 'DJ et public' },
            { url: 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&q=80', alt: 'Foule en soirée' },
            { url: 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&q=80', alt: 'Ambiance festive' },
            { url: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&q=80', alt: 'Concert en soirée' },
        ];

        // Afficher la galerie (lecture seule pour les clients)
        function renderGallery(images) {
            const container = document.getElementById('gallery-container');
            if (!container) return;

            container.innerHTML = images.map((img) => `
                <div class="card" style="padding: 0; overflow: hidden; position: relative; transition: transform 0.3s ease, box-shadow 0.3s ease;" 
                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,0.15)'"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'">
                    <img src="${img.url}" alt="${img.alt || 'Image de soirée'}" 
                         style="width: 100%; height: 300px; object-fit: cover; display: block; transition: transform 0.5s ease;" 
                         loading="lazy"
                         onmouseover="this.style.transform='scale(1.1)'"
                         onmouseout="this.style.transform='scale(1)'" />
                </div>
            `).join('');
        }

        // Charger l'image hero sauvegardée (si admin l'a modifiée)
        function loadHeroImage() {
            const saved = localStorage.getItem('wins-events-hero-image');
            const heroImg = document.getElementById('hero-image');
            if (saved && heroImg) {
                heroImg.src = saved;
            }
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            renderGallery(defaultImages);
            loadHeroImage();
        });
    </script>

    <style>
        @media (max-width: 768px) {
            .card[style*="grid-template-columns: 1.2fr 1fr"] > div:first-child {
                padding: 32px 24px !important;
            }
            .card[style*="grid-template-columns: 1.2fr 1fr"] {
                grid-template-columns: 1fr !important;
                min-height: auto !important;
            }
            h1[style*="font-size: 48px"] {
                font-size: 32px !important;
            }
            h2[style*="font-size: 36px"], h3[style*="font-size: 32px"] {
                font-size: 28px !important;
            }
        }
    </style>
@endsection
