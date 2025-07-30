<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $websiteSettings->website_title ?? 'Event Hoki Talas89' }}</title>
    @if($websiteSettings->favicon_path)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $websiteSettings->favicon_path) }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ url('/css/custom.css') }}">
    <style>
        /* Slot Machine Background Animations */
        @keyframes slotSpin {
            0% { transform: translateY(0); }
            100% { transform: translateY(-100%); }
        }

        @keyframes casinoLights {
            0%, 100% { 
                background-position: 0% 0%, 25% 25%, 50% 50%, 75% 75%;
                opacity: 0.8;
            }
            25% { 
                background-position: 25% 25%, 50% 50%, 75% 75%, 0% 0%;
                opacity: 1;
            }
            50% { 
                background-position: 50% 50%, 75% 75%, 0% 0%, 25% 25%;
                opacity: 0.9;
            }
            75% { 
                background-position: 75% 75%, 0% 0%, 25% 25%, 50% 50%;
                opacity: 1;
            }
        }

        @keyframes jackpotGlow {
            0%, 100% {
                box-shadow: 0 0 20px #488c2c, 0 0 40px #488c2c, 0 0 60px #488c2c;
                text-shadow: 0 0 10px #488c2c;
            }
            50% {
                box-shadow: 0 0 30px #6ba942, 0 0 60px #6ba942, 0 0 90px #6ba942;
                text-shadow: 0 0 15px #6ba942;
            }
        }

        @keyframes coinFall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        @keyframes reelSpin {
            0% { transform: rotateX(0deg); }
            100% { transform: rotateX(360deg); }
        }

        @keyframes gentleGlow {
            0%, 100% {
                opacity: 0.7;
                text-shadow: 0 0 10px rgba(72, 140, 44, 0.8);
            }
            50% {
                opacity: 1;
                text-shadow: 0 0 15px rgba(107, 169, 66, 1);
            }
        }

        /* Gift Box Opening/Closing Animations */
        @keyframes giftBoxClose {
            0% {
                transform: perspective(600px) rotateX(0deg) rotateY(0deg);
            }
            50% {
                transform: perspective(600px) rotateX(-15deg) rotateY(5deg) scale(0.95);
            }
            100% {
                transform: perspective(600px) rotateX(-90deg) rotateY(0deg) scale(0.9);
            }
        }

        @keyframes giftBoxOpen {
            0% {
                transform: perspective(600px) rotateX(-90deg) rotateY(0deg) scale(0.9);
            }
            30% {
                transform: perspective(600px) rotateX(-45deg) rotateY(2deg) scale(0.95);
            }
            60% {
                transform: perspective(600px) rotateX(10deg) rotateY(-2deg) scale(1.02);
            }
            100% {
                transform: perspective(600px) rotateX(0deg) rotateY(0deg) scale(1);
            }
        }

        @keyframes giftBoxLidOpen {
            0% {
                transform: perspective(600px) rotateX(0deg) scale(1);
                opacity: 1;
            }
            50% {
                transform: perspective(600px) rotateX(-45deg) scale(1.1);
                opacity: 0.8;
            }
            100% {
                transform: perspective(600px) rotateX(-90deg) scale(1.2);
                opacity: 0;
            }
        }

        @keyframes prizeImageReveal {
            0% {
                opacity: 0;
                transform: scale(0.3) translateY(20px);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.1) translateY(-5px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0px);
            }
        }

        /* Prize Reveal Animations */
        @keyframes prizeRevealContainer {
            0% {
                opacity: 0;
                transform: scale(0.3) rotateY(-180deg);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.1) rotateY(-90deg);
            }
            100% {
                opacity: 1;
                transform: scale(1) rotateY(0deg);
            }
        }

        @keyframes prizeBoxReveal {
            0% {
                opacity: 0;
                transform: translateY(50px) scale(0.5) rotateX(-90deg);
            }
            60% {
                opacity: 0.8;
                transform: translateY(-10px) scale(1.1) rotateX(10deg);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1) rotateX(0deg);
            }
        }

        @keyframes prizeImageFloat {
            0%, 100% {
                transform: translateY(0px) scale(1);
            }
            50% {
                transform: translateY(-10px) scale(1.05);
            }
        }

        @keyframes sparkleExplosion {
            0% {
                opacity: 0;
                transform: scale(0) rotate(0deg);
            }
            50% {
                opacity: 1;
                transform: scale(1.5) rotate(180deg);
            }
            100% {
                opacity: 0;
                transform: scale(0.5) rotate(360deg);
            }
        }

        @keyframes prizeGlow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(72, 140, 44, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(72, 140, 44, 0.8), 0 0 60px rgba(107, 169, 66, 0.4);
            }
        }

        /* Casino Slot Machine Background */
        body {
            background: var(--enable-dark-mode) == 'true' ? 
                radial-gradient(circle at 20% 20%, rgba(72, 140, 44, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(107, 169, 66, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 20% 80%, rgba(20, 42, 14, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(72, 140, 44, 0.15) 0%, transparent 50%),
                linear-gradient(135deg, #142a0e 0%, #1f3f16 25%, #2a5420 50%, #142a0e 75%, #1f3f16 100%)
                : var(--background-color);
            background-size: 200% 200%, 200% 200%, 200% 200%, 200% 200%, 400% 400%;
            animation: casinoLights 4s ease-in-out infinite;
            font-family: var(--font-family);
            text-align: center;
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 15px 0;
            overflow-x: hidden;
            position: relative;
        }

        /* Slot Machine Reels Background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                repeating-linear-gradient(
                    90deg,
                    transparent 0px,
                    rgba(72, 140, 44, 0.05) 20px,
                    transparent 40px,
                    rgba(107, 169, 66, 0.05) 60px,
                    transparent 80px
                ),
                repeating-linear-gradient(
                    0deg,
                    transparent 0px,
                    rgba(20, 42, 14, 0.03) 30px,
                    transparent 60px,
                    rgba(72, 140, 44, 0.03) 90px,
                    transparent 120px
                );
            animation: slotSpin 8s linear infinite;
            pointer-events: none;
            opacity: 0.3;
        }

        /* Casino Coins Animation */
        .casino-coin {
            position: absolute;
            width: 20px;
            height: 20px;
            background: radial-gradient(circle, #488c2c 0%, #6ba942 50%, #4a7a2f 100%);
            border-radius: 50%;
            pointer-events: none;
            animation: coinFall 6s linear infinite;
            box-shadow: 0 0 10px rgba(72, 140, 44, 0.8);
        }

        .casino-coin:nth-child(1) { left: 10%; animation-delay: 0s; }
        .casino-coin:nth-child(2) { left: 20%; animation-delay: 1s; }
        .casino-coin:nth-child(3) { left: 30%; animation-delay: 2s; }
        .casino-coin:nth-child(4) { left: 40%; animation-delay: 3s; }
        .casino-coin:nth-child(5) { left: 50%; animation-delay: 4s; }
        .casino-coin:nth-child(6) { left: 60%; animation-delay: 0.5s; }
        .casino-coin:nth-child(7) { left: 70%; animation-delay: 1.5s; }
        
        /* Right side coins with reduced animation to prevent "moving taskbar" effect */
        .casino-coin:nth-child(8) { 
            left: 80%; 
            animation: coinFall 12s linear infinite;
            animation-delay: 2.5s; 
        }
        .casino-coin:nth-child(9) { 
            left: 90%; 
            animation: coinFall 12s linear infinite;
            animation-delay: 6s; 
        }

        /* Slot Machine Symbols */
        .slot-symbol {
            position: absolute;
            font-size: 30px;
            color: #488c2c;
            pointer-events: none;
            animation: reelSpin 2s linear infinite;
            text-shadow: 0 0 10px rgba(72, 140, 44, 0.8);
        }

        /* Left side symbols with normal animation */
        .slot-symbol:nth-child(10) { top: 10%; left: 5%; animation-delay: 0s; }
        .slot-symbol:nth-child(12) { top: 70%; left: 3%; animation-delay: 1s; }
        
        /* Right side symbols with reduced animation to prevent "moving taskbar" effect */
        .slot-symbol:nth-child(11) { 
            top: 20%; 
            left: 95%; 
            animation: gentleGlow 3s ease-in-out infinite;
            animation-delay: 0.5s;
        }
        .slot-symbol:nth-child(13) { 
            top: 80%; 
            left: 97%; 
            animation: gentleGlow 3s ease-in-out infinite;
            animation-delay: 1.5s;
        }

        /* Main Container - Slot Machine Style */
        .container {
            width: 95%;
            max-width: 1000px;
            text-align: center;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(5px);
            background: 
                linear-gradient(145deg, rgba(20, 42, 14, 0.9) 0%, rgba(31, 63, 22, 0.9) 50%, rgba(42, 84, 32, 0.9) 100%);
            border-radius: 25px;
            padding: 20px;
            border: 3px solid #488c2c;
            animation: jackpotGlow 3s ease-in-out infinite;
            box-shadow: 
                inset 0 0 20px rgba(72, 140, 44, 0.2),
                0 0 30px rgba(72, 140, 44, 0.3),
                0 0 60px rgba(107, 169, 66, 0.2);
        }

        /* Casino Style Header */
        .header {
            font-size: 28px;
            margin-bottom: 15px;
            color: var(--header-text-color);
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Website Image */
        .website-image img {
            max-width: 300px;
            max-height: 200px;
            width: auto;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }
        
        .website-image img:hover {
            transform: scale(1.05);
        }

        /* Time Display */
        .time-display {
            background: linear-gradient(145deg, rgba(72, 140, 44, 0.1), rgba(107, 169, 66, 0.1));
            border: 2px solid #488c2c;
            border-radius: 15px;
            padding: 12px;
            margin-bottom: 15px;
            text-align: center;
            box-shadow: 0 0 15px rgba(72, 140, 44, 0.3);
        }

        .time-display h3 {
            color: #488c2c;
            font-size: 16px;
            margin-bottom: 8px;
            text-shadow: 0 0 8px rgba(72, 140, 44, 0.8);
        }

        .time-display p {
            color: #6ba942;
            font-size: 14px;
            margin: 0;
            text-shadow: 0 0 5px rgba(107, 169, 66, 0.8);
        }

        .current-time {
            font-size: 18px;
            font-weight: bold;
            color: #4a7a2f;
        }

        /* Casino Style Buttons */
        .btn {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            margin: 8px 0;
            border-radius: 15px;
            border: 2px solid #488c2c;
            box-sizing: border-box;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--button-background-color);
            color: var(--button-text-color);
            box-shadow: 0 5px 15px rgba(72, 140, 44, 0.4);
        }

        .btn-primary:hover {
            background: var(--button-background-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(72, 140, 44, 0.6);
            opacity: 0.9;
        }

        .btn-danger {
            background: linear-gradient(145deg, #5a2d1a 0%, #7a3d2a 50%, #6a3520 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(90, 45, 26, 0.4);
        }

        .btn-danger:hover {
            background: linear-gradient(145deg, #7a3d2a 0%, #5a2d1a 50%, #6a3520 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(122, 61, 42, 0.6);
        }

        .btn-info {
            background: linear-gradient(145deg, #2a5420 0%, #3d7a30 50%, #357a28 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(42, 84, 32, 0.4);
        }

        .btn-info:hover {
            background: linear-gradient(145deg, #3d7a30 0%, #2a5420 50%, #357a28 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(61, 122, 48, 0.6);
        }

        /* Form Container */
        .form-container {
            display: none;
            margin-top: 15px;
            animation: fadeInUp 0.8s ease-out;
            background: rgba(20, 42, 14, 0.8);
            padding: 18px;
            border-radius: 15px;
            border: 2px solid #488c2c;
            box-shadow: 0 0 20px rgba(72, 140, 44, 0.3);
        }

        .button-container {
            margin: 15px 0;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container h3 {
            margin-bottom: 15px;
            color: #488c2c;
            font-size: 20px;
            text-shadow: 0 0 10px rgba(72, 140, 44, 0.8);
        }

        .notice {
            color: #7a3d2a;
            display: none;
            margin-top: 10px;
            text-shadow: 0 0 10px rgba(122, 61, 42, 0.8);
        }

        /* Social Media Buttons in Notice */
        .notice .social-media-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .notice .social-media-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .notice .whatsapp-btn {
            color: #25D366;
            background: rgba(37, 211, 102, 0.1);
            border-color: #25D366;
        }

        .notice .whatsapp-btn:hover {
            background: rgba(37, 211, 102, 0.2);
        }

        .notice .telegram-btn {
            color: #0088cc;
            background: rgba(0, 136, 204, 0.1);
            border-color: #0088cc;
        }

        .notice .telegram-btn:hover {
            background: rgba(0, 136, 204, 0.2);
        }

        /* Gift Container - Enhanced Slot Machine Style */
        .gift-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 180px));
            gap: 20px;
            margin-top: 15px;
            opacity: 0;
            transform: scale(0.3) rotateY(-180deg);
            transition: all 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            justify-items: center;
            justify-content: center;
            background: rgba(20, 42, 14, 0.6);
            padding: 25px;
            border-radius: 15px;
            border: 4px solid #488c2c;
            position: relative;
        }

        .gift-container::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(45deg, #488c2c, #6ba942, #4a7a2f, #357a28);
            border-radius: 20px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .gift-container.show,
        .gift-container.show-closed {
            opacity: 1;
            transform: scale(1) rotateY(0deg);
        }

        .gift-container.show::before,
        .gift-container.show-closed::before {
            opacity: 0.3;
            animation: prizeGlow 2s ease-in-out infinite;
        }

        .gift-box {
            background: linear-gradient(145deg, rgba(72, 140, 44, 0.1), rgba(107, 169, 66, 0.1));
            padding: 20px;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 160px;
            height: 160px;
            box-sizing: border-box;
            border: 4px solid #488c2c;
            box-shadow: 0 0 20px rgba(72, 140, 44, 0.4);
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(50px) scale(0.5) rotateX(-90deg);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            cursor: pointer;
        }

        .gift-box.closed::before {
            content: '🎁';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 60px;
            opacity: 1;
            transition: all 0.5s ease;
            z-index: 2;
        }

        .gift-box.opened::before {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0);
        }

        .gift-box::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
            z-index: 1;
        }

        .gift-container.show-closed .gift-box {
            animation: prizeBoxReveal 1s ease-out forwards;
        }

        .gift-container.show-closed .gift-box:nth-child(1) { animation-delay: 0.2s; }
        .gift-container.show-closed .gift-box:nth-child(2) { animation-delay: 0.4s; }
        .gift-container.show-closed .gift-box:nth-child(3) { animation-delay: 0.6s; }
        .gift-container.show-closed .gift-box:nth-child(4) { animation-delay: 0.8s; }
        .gift-container.show-closed .gift-box:nth-child(5) { animation-delay: 1.0s; }

        .gift-box:hover::after {
            left: 100%;
        }

        .gift-box:hover {
            transform: scale(1.05) rotateY(10deg);
            box-shadow: 0 0 30px rgba(72, 140, 44, 0.6);
            border-color: #6ba942;
        }

        .gift-box img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 0 10px rgba(72, 140, 44, 0.5));
            transition: all 0.3s ease;
            opacity: 0;
            z-index: 3;
            position: relative;
        }

        .gift-box.opened img {
            opacity: 1;
            animation: prizeImageReveal 1s ease-out forwards, prizeImageFloat 3s ease-in-out 1s infinite;
        }

        /* Sparkle effects for prize reveal */
        .sparkle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: #488c2c;
            border-radius: 50%;
            pointer-events: none;
            animation: sparkleExplosion 1.5s ease-out forwards;
        }

        /* Congratulatory Message Styling - Popup Modal */
        .congratulations-message {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .congratulations-message.show {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            animation: modalFadeIn 0.5s ease-out;
        }

        @keyframes modalFadeIn {
            0% {
                opacity: 0;
                backdrop-filter: blur(0px);
            }
            100% {
                opacity: 1;
                backdrop-filter: blur(5px);
            }
        }

        .congrats-content {
            background: linear-gradient(145deg, rgba(72, 140, 44, 0.95), rgba(107, 169, 66, 0.95));
            padding: 30px;
            border-radius: 20px;
            border: 3px solid #488c2c;
            box-shadow: 0 0 30px rgba(72, 140, 44, 0.5);
            text-align: center;
            position: relative;
            max-width: 400px;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.7);
            animation: congratsPopup 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        @keyframes congratsPopup {
            0% {
                transform: scale(0.7) translateY(50px) rotateX(-90deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.05) translateY(-10px) rotateX(10deg);
                opacity: 0.8;
            }
            100% {
                transform: scale(1) translateY(0) rotateX(0deg);
                opacity: 1;
            }
        }

        .congrats-close {
            position: absolute;
            top: 10px;
            right: 15px;
            background: rgba(122, 61, 42, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .congrats-close:hover {
            background: rgba(122, 61, 42, 1);
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(122, 61, 42, 0.4);
        }

        .congrats-content h2 {
            color: #142a0e;
            font-size: 32px;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: congratsTextGlow 2s ease-in-out infinite;
        }

        @keyframes congratsTextGlow {
            0%, 100% {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            }
            50% {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3), 0 0 20px rgba(72, 140, 44, 0.8);
            }
        }

        .congrats-content p {
            color: #142a0e;
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .prize-display {
            background: rgba(20, 42, 14, 0.8);
            padding: 20px;
            border-radius: 15px;
            border: 2px solid #488c2c;
            margin-top: 20px;
        }

        .prize-display img {
            max-width: 150px;
            max-height: 150px;
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 15px;
            filter: drop-shadow(0 0 15px rgba(72, 140, 44, 0.7));
            animation: prizeWonFloat 3s ease-in-out infinite;
        }

        @keyframes prizeWonFloat {
            0%, 100% {
                transform: translateY(0px) scale(1);
            }
            50% {
                transform: translateY(-8px) scale(1.05);
            }
        }

        .prize-display h3 {
            color: #488c2c;
            font-size: 24px;
            margin: 0;
            text-shadow: 0 0 10px rgba(72, 140, 44, 0.8);
        }

        /* Game Rules Modal Styling */
        .game-rules-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .game-rules-modal.show {
            display: flex;
            animation: modalFadeIn 0.5s ease-out;
        }

        .rules-content {
            background: linear-gradient(145deg, rgba(42, 84, 32, 0.95), rgba(61, 122, 48, 0.95));
            padding: 30px;
            border-radius: 20px;
            border: 3px solid #488c2c;
            box-shadow: 0 0 30px rgba(72, 140, 44, 0.5);
            position: relative;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.7);
            animation: rulesPopup 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        @keyframes rulesPopup {
            0% {
                transform: scale(0.7) translateY(50px) rotateX(-90deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.05) translateY(-10px) rotateX(10deg);
                opacity: 0.8;
            }
            100% {
                transform: scale(1) translateY(0) rotateX(0deg);
                opacity: 1;
            }
        }

        .rules-close {
            position: absolute;
            top: 10px;
            right: 15px;
            background: rgba(122, 61, 42, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .rules-close:hover {
            background: rgba(122, 61, 42, 1);
            transform: scale(1.1);
        }

        .rules-content h2 {
            color: #488c2c;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
            text-shadow: 0 0 10px rgba(72, 140, 44, 0.8);
        }

        .rules-content h3 {
            color: #FFFFFF;
            font-size: 20px;
            margin: 20px 0 10px 0;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        .rules-text {
            color: #FFFFFF;
            font-size: 16px;
            line-height: 1.6;
            text-align: left;
        }

        .rules-text ol, .rules-text ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .rules-text li {
            margin: 8px 0;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
        }

        .rules-text strong {
            color: #488c2c;
            text-shadow: 0 0 5px rgba(72, 140, 44, 0.8);
        }

        /* Prize List Container (for TAMPILKAN HADIAH button) */
        .prize-list-container {
            display: none;
            margin-top: 5px;
            padding: 15px;
            background: linear-gradient(145deg, rgba(20, 42, 14, 0.95), rgba(31, 63, 22, 0.95));
            border-radius: 10px;
            border: 2px solid #488c2c;
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease-out;
            box-shadow: 
                0 0 15px rgba(72, 140, 44, 0.3),
                inset 0 0 10px rgba(72, 140, 44, 0.1);
            max-height: 50vh;
            overflow-y: auto;
        }

        .prize-list-container.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
            animation: prizeListReveal 0.8s ease-out, prizeContainerGlow 2s ease-in-out 0.8s infinite;
        }

        @keyframes prizeListReveal {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes prizeContainerGlow {
            0%, 100% {
                box-shadow: 0 0 15px rgba(72, 140, 44, 0.3), inset 0 0 10px rgba(72, 140, 44, 0.1);
            }
            50% {
                box-shadow: 0 0 25px rgba(72, 140, 44, 0.5), inset 0 0 15px rgba(72, 140, 44, 0.2);
            }
        }

        .prize-list-container h3 {
            color: #488c2c;
            font-size: 18px;
            margin-bottom: 12px;
            text-shadow: 
                0 0 8px rgba(72, 140, 44, 0.8),
                1px 1px 2px rgba(0, 0, 0, 0.5);
            animation: congratsTextGlow 2s ease-in-out infinite;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .prize-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 140px));
            gap: 15px;
            justify-items: center;
            justify-content: center;
            max-width: 100%;
            margin: 0 auto;
            padding: 15px;
        }

        .prize-item {
            background: linear-gradient(145deg, rgba(72, 140, 44, 0.15), rgba(107, 169, 66, 0.15));
            padding: 12px;
            border-radius: 10px;
            border: 2px solid #488c2c;
            box-shadow: 
                0 3px 10px rgba(72, 140, 44, 0.3),
                inset 0 1px 3px rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 120px;
            width: 100%;
            max-width: 140px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .prize-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.4s;
        }

        .prize-item:hover::before {
            left: 100%;
        }

        .prize-item:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 
                0 4px 12px rgba(72, 140, 44, 0.4),
                inset 0 1px 3px rgba(255, 255, 255, 0.2);
            border-color: #6ba942;
        }

        .prize-item img {
            max-width: 90px;
            max-height: 70px;
            object-fit: contain;
            margin-bottom: 8px;
            filter: drop-shadow(0 0 5px rgba(72, 140, 44, 0.5));
            transition: all 0.3s ease;
            border-radius: 5px;
        }

        .prize-item:hover img {
            transform: scale(1.05);
            filter: drop-shadow(0 0 8px rgba(72, 140, 44, 0.7));
        }

        .prize-item span {
            color: #488c2c;
            font-size: 12px;
            font-weight: bold;
            text-shadow: 
                0 0 3px rgba(72, 140, 44, 0.8),
                1px 1px 2px rgba(0, 0, 0, 0.3);
            line-height: 1.3;
            display: block;
            text-align: center;
            max-width: 100%;
            word-wrap: break-word;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 6px;
            border-radius: 5px;
            margin-top: 4px;
        }

        /* Animated Prize Gift Box Styles */
        .prize-gift-box {
            background: linear-gradient(145deg, rgba(72, 140, 44, 0.1), rgba(107, 169, 66, 0.1));
            padding: 15px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 200px;
            height: 200px;
            box-sizing: border-box;
            border: 3px solid #488c2c;
            box-shadow: 
                0 0 20px rgba(72, 140, 44, 0.4),
                inset 0 2px 4px rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: visible;
            transform-style: preserve-3d;
            cursor: pointer;
            margin: 10px;
        }

        .prize-gift-box.closed {
            background: linear-gradient(145deg, rgba(72, 140, 44, 0.2), rgba(107, 169, 66, 0.2));
            animation: prizeBoxPulse 3s ease-in-out infinite;
            border-color: #488c2c;
        }

        .prize-gift-box.opening {
            animation: prizeBoxOpen 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .prize-gift-box.opened {
            background: linear-gradient(145deg, rgba(72, 140, 44, 0.15), rgba(107, 169, 66, 0.15));
            border-color: #6ba942;
            box-shadow: 
                0 0 40px rgba(72, 140, 44, 0.6),
                0 0 60px rgba(107, 169, 66, 0.3),
                inset 0 2px 4px rgba(255, 255, 255, 0.2);
        }

        .gift-icon {
            font-size: 80px;
            animation: giftIconFloat 3s ease-in-out infinite;
            text-shadow: 0 0 15px rgba(72, 140, 44, 0.8);
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .gift-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.3);
            width: 100%;
            height: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .gift-content.hidden {
            display: none;
        }

        .prize-image {
            max-width: 120px;
            max-height: 100px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin-bottom: 12px;
            filter: drop-shadow(0 0 8px rgba(72, 140, 44, 0.6));
            border-radius: 12px;
            border: 2px solid rgba(72, 140, 44, 0.3);
            background: rgba(255, 255, 255, 0.9);
            padding: 4px;
        }

        .prize-name {
            color: #2d5016;
            font-size: 14px;
            font-weight: bold;
            text-shadow: 
                0 0 5px rgba(72, 140, 44, 0.8),
                1px 1px 2px rgba(0, 0, 0, 0.5);
            line-height: 1.3;
            display: block;
            text-align: center;
            background: rgba(72, 140, 44, 0.1);
            padding: 6px 8px;
            border-radius: 8px;
            border: 1px solid rgba(72, 140, 44, 0.3);
            backdrop-filter: blur(2px);
            max-width: 100%;
            word-wrap: break-word;
        }

        .sparkle-effect {
            position: absolute;
            font-size: 16px;
            pointer-events: none;
            animation: sparkleFloat 1.5s ease-out forwards;
            z-index: 10;
        }

        /* Prize Gift Box Animations */
        @keyframes prizeBoxPulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 20px rgba(72, 140, 44, 0.4);
            }
            50% {
                transform: scale(1.02);
                box-shadow: 0 0 25px rgba(72, 140, 44, 0.6);
            }
        }

        @keyframes prizeBoxOpen {
            0% {
                transform: perspective(600px) rotateY(0deg) scale(1);
            }
            30% {
                transform: perspective(600px) rotateY(-10deg) scale(1.05);
            }
            60% {
                transform: perspective(600px) rotateY(5deg) scale(0.98);
            }
            100% {
                transform: perspective(600px) rotateY(0deg) scale(1);
            }
        }

        @keyframes prizeBoxShake {
            0%, 100% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-2px);
            }
            75% {
                transform: translateX(2px);
            }
        }

        @keyframes giftIconFloat {
            0%, 100% {
                transform: translateY(0px) scale(1);
            }
            50% {
                transform: translateY(-5px) scale(1.1);
            }
        }

        @keyframes giftIconDisappear {
            0% {
                opacity: 1;
                transform: scale(1) rotateY(0deg);
            }
            50% {
                opacity: 0.5;
                transform: scale(1.2) rotateY(180deg);
            }
            100% {
                opacity: 0;
                transform: scale(0) rotateY(360deg);
            }
        }

        @keyframes prizeContentReveal {
            0% {
                opacity: 0;
                transform: scale(0.3) translateY(20px);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.1) translateY(-5px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0px);
            }
        }

        @keyframes sparkleFloat {
            0% {
                opacity: 0;
                transform: translateY(0px) scale(0);
            }
            20% {
                opacity: 1;
                transform: translateY(-10px) scale(1);
            }
            80% {
                opacity: 1;
                transform: translateY(-20px) scale(1.2);
            }
            100% {
                opacity: 0;
                transform: translateY(-30px) scale(0);
            }
        }

        /* Input Field Styling */
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #488c2c;
            border-radius: 10px;
            background: rgba(20, 42, 14, 0.3);
            color: #488c2c;
            text-align: center;
            font-weight: bold;
            box-sizing: border-box;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #6ba942;
            background: rgba(20, 42, 14, 0.5);
            box-shadow: 0 0 15px rgba(72, 140, 44, 0.4);
            transform: scale(1.02);
        }

        input[type="text"]::placeholder {
            color: rgba(72, 140, 44, 0.7);
            font-style: italic;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .container {
                width: 98%;
                padding: 15px;
                margin: 10px auto;
            }

            .header {
                font-size: 24px;
                margin-bottom: 12px;
            }

            .website-image img {
                max-width: 200px;
                max-height: 130px;
            }

            .btn {
                font-size: 16px;
                height: 55px;
                padding: 12px;
            }

            .gift-container {
                grid-template-columns: repeat(auto-fit, minmax(140px, 140px));
                gap: 15px;
                padding: 20px;
            }

            .gift-box {
                width: 140px;
                height: 140px;
                padding: 15px;
            }

            .gift-box.closed::before {
                font-size: 50px;
            }

            .congrats-content {
                max-width: 90%;
                padding: 20px;
                margin: 20px;
            }

            .rules-content {
                max-width: 90%;
                padding: 20px;
                margin: 20px;
            }

            .prize-list {
                grid-template-columns: repeat(auto-fit, minmax(120px, 120px));
                gap: 12px;
                padding: 10px;
            }

            .prize-item {
                min-height: 100px;
                max-width: 120px;
                padding: 10px;
                margin: 0;
                border: 2px solid #488c2c;
            }

            .prize-item img {
                max-width: 75px;
                max-height: 55px;
            }

            .prize-item span {
                font-size: 11px;
                padding: 3px 5px;
            }

            .time-display {
                padding: 10px;
            }

            .time-display h3 {
                font-size: 14px;
            }

            .time-display p {
                font-size: 12px;
            }

            /* Mobile styles for prize gift boxes */
            .prize-gift-box {
                width: 180px;
                height: 180px;
                padding: 12px;
                margin: 8px;
            }

            .gift-icon {
                font-size: 70px;
            }

            .prize-image {
                max-width: 100px;
                max-height: 80px;
                margin-bottom: 10px;
            }

            .prize-name {
                font-size: 13px;
                padding: 5px 6px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 98%;
                padding: 12px;
            }

            .header {
                font-size: 20px;
                letter-spacing: 1px;
            }

            .website-image img {
                max-width: 150px;
                max-height: 100px;
            }

            .btn {
                font-size: 14px;
                height: 50px;
                margin: 6px 0;
            }

            .gift-container {
                grid-template-columns: repeat(auto-fit, minmax(120px, 120px));
                gap: 12px;
                padding: 15px;
            }

            /* Mobile responsive styles for social media buttons */
            .notice .social-media-btn {
                font-size: 12px;
                padding: 6px 10px;
                gap: 4px;
            }

            .notice .social-media-btn span {
                font-size: 11px;
            }

            .notice .social-media-btn i {
                font-size: 14px !important;
            }

            /* Mobile responsive styles for prize gift boxes */
            .prize-gift-box {
                width: 160px;
                height: 160px;
                padding: 10px;
                margin: 6px;
            }

            .gift-icon {
                font-size: 60px;
            }

            .prize-image {
                max-width: 90px;
                max-height: 70px;
                margin-bottom: 8px;
            }

            .prize-name {
                font-size: 12px;
                padding: 4px 5px;
                line-height: 1.2;
            }

            .gift-box {
                width: 120px;
                height: 120px;
                padding: 12px;
            }

            .gift-box.closed::before {
                font-size: 40px;
            }

            .prize-list {
                grid-template-columns: repeat(auto-fit, minmax(110px, 110px));
                gap: 10px;
                padding: 8px;
            }

            .prize-item {
                min-height: 90px;
                max-width: 110px;
                padding: 8px;
                margin: 0;
                border: 2px solid #488c2c;
            }

            .prize-item img {
                max-width: 65px;
                max-height: 50px;
            }

            .prize-item span {
                font-size: 10px;
                padding: 2px 4px;
            }
        }
    </style>
    <!-- Dynamic Custom CSS from Admin Customization - Inline for immediate effect -->
    <style>
        {!! \App\Models\WebsiteCustomization::generateCSS() !!}
    </style>
</head>
<body>
    <!-- Casino Coins Animation -->
    <div class="casino-coin"></div>
    <div class="casino-coin"></div>
    <div class="casino-coin"></div>
    <div class="casino-coin"></div>
    <div class="casino-coin"></div>
    <div class="casino-coin"></div>
    <div class="casino-coin"></div>
    <div class="casino-coin"></div>
    <div class="casino-coin"></div>

    <!-- Slot Machine Symbols -->
    <div class="slot-symbol">🎰</div>
    <div class="slot-symbol">💰</div>
    <div class="slot-symbol">🎲</div>
    <div class="slot-symbol">🍀</div>
    <div class="container">
        <!-- Website Image -->
        @php
            $websiteImage = \App\Models\WebsiteCustomization::getSetting('website_image');
        @endphp
        @if($websiteImage)
            <div class="website-image" style="text-align: center; margin-bottom: 20px;">
                <img src="{{ asset('storage/' . $websiteImage) }}" alt="Website Image" style="max-width: 300px; max-height: 200px; width: auto; height: auto; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
            </div>
        @endif
        
        <h4 class="header">
            🎰 {{ strtoupper($websiteSettings->website_title ?? 'EVENT HOKI TALAS89') }} 🎰
        </h4>

        <!-- Time Display -->
        @if(\App\Models\WebsiteCustomization::getSetting('show_time_display', 'true') === 'true')
        <div class="time-display">
            <h3><i class="fas fa-clock"></i> Waktu Saat Ini</h3>
            <p>Hari: <span id="current-day"></span></p>
            <p class="current-time" id="current-time"></p>
        </div>
        @endif

        <!-- Main Buttons -->
        <div class="button-container">
            <button class="btn btn-primary" onclick="showTicketForm()">
                <i class="fas fa-ticket-alt"></i> MASUKKAN KODE TIKET
            </button>
            
            <button class="btn btn-info" onclick="showPrizeList()">
                <i class="fas fa-gift"></i> TAMPILKAN HADIAH
            </button>
            
            <button class="btn btn-danger" onclick="showGameRules()">
                <i class="fas fa-info-circle"></i> ATURAN BERMAIN
            </button>
        </div>

        <!-- Ticket Input Form -->
        <div id="ticket-form" class="form-container">
            <h3><i class="fas fa-ticket-alt"></i> Masukkan Kode Tiket Anda</h3>
            <input type="text" id="ticket-code" placeholder="Masukkan kode tiket..." maxlength="10">
            <button class="btn btn-primary" onclick="validateTicket()" style="margin-top: 10px;">
                <i class="fas fa-check"></i> VALIDASI TIKET
            </button>
            <div id="ticket-notice" class="notice"></div>
        </div>

        <!-- Prize List Container -->
        <div id="prize-list-container" class="prize-list-container">
            <h3><i class="fas fa-trophy"></i> Daftar Hadiah Event</h3>
            <div class="prize-list" id="prize-list">
                <!-- Prize items will be loaded here -->
            </div>
            <!-- Controls -->
            <div style="margin-top: 15px; text-align: center;">
                <button onclick="loadGifts(); console.log('Manual reload triggered');" 
                        style="background: rgba(72, 140, 44, 0.8); color: white; border: 1px solid #488c2c; padding: 5px 10px; border-radius: 5px; font-size: 12px;">
                    <i class="fas fa-sync"></i> Refresh Hadiah
                </button>
            </div>
        </div>

        <!-- Gift Container -->
        <div id="gift-container" class="gift-container">
            <!-- Gift boxes will be populated here -->
        </div>
    </div>

    <!-- Congratulations Modal -->
    <div id="congratulations-message" class="congratulations-message">
        <div class="congrats-content">
            <button class="congrats-close" onclick="closeCongratulations()">&times;</button>
            <h2><i class="fas fa-trophy"></i> SELAMAT!</h2>
            <p>Anda memenangkan hadiah:</p>
            <div class="prize-display">
                <img id="won-prize-image" src="" alt="Prize">
                <h3 id="won-prize-name"></h3>
            </div>
        </div>
    </div>

    <!-- Game Rules Modal -->
    <div id="game-rules-modal" class="game-rules-modal">
        <div class="rules-content">
            <button class="rules-close" onclick="closeGameRules()">&times;</button>
            <h2><i class="fas fa-scroll"></i> ATURAN BERMAIN</h2>
            <div class="rules-text">
                @if($websiteSettings->game_rules_content)
                    {!! $websiteSettings->game_rules_content !!}
                @else
                    <h3><i class="fas fa-play-circle"></i> Cara Bermain:</h3>
                    <ol>
                        <li>Masukkan <strong>kode tiket</strong> yang valid pada form yang tersedia</li>
                        <li>Klik tombol <strong>"VALIDASI TIKET"</strong> untuk memverifikasi kode</li>
                        <li>Jika kode valid, akan muncul <strong>5 kotak hadiah</strong> tertutup</li>
                        <li><strong>Klik salah satu kotak</strong> untuk membuka dan melihat hadiah Anda</li>
                        <li>Setiap kode tiket <strong>hanya dapat digunakan sekali</strong></li>
                    </ol>

                    <h3><i class="fas fa-exclamation-triangle"></i> Penting:</h3>
                    <ul>
                        <li>Pastikan kode tiket yang Anda masukkan <strong>benar dan valid</strong></li>
                        <li>Kode tiket yang sudah digunakan <strong>tidak dapat digunakan lagi</strong></li>
                        <li>Hadiah yang sudah dipilih <strong>tidak dapat diganti</strong></li>
                        <li>Event ini berlaku selama <strong>periode yang ditentukan</strong></li>
                    </ul>

                    <h3><i class="fas fa-gift"></i> Tentang Hadiah:</h3>
                    <ul>
                        <li>Setiap tiket memiliki <strong>hadiah yang sudah ditentukan</strong></li>
                        <li>Anda dapat melihat daftar semua hadiah dengan klik <strong>"TAMPILKAN HADIAH"</strong></li>
                        <li>Hadiah akan ditampilkan setelah Anda membuka kotak</li>
                    </ul>

                    <h3><i class="fas fa-phone"></i> Bantuan:</h3>
                    <p>Jika mengalami masalah, silakan hubungi admin untuk bantuan lebih lanjut.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentTicketCode = '';
        let currentTicketPrize = '';
        let isTicketValidated = false;
        let gifts = [];

        // Load gifts data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
            setInterval(updateTime, 1000);
            
            // Load gifts with retry mechanism
            loadGiftsWithRetry();
        });
        
        // Load gifts with retry mechanism
        async function loadGiftsWithRetry(maxRetries = 3) {
            for (let attempt = 1; attempt <= maxRetries; attempt++) {
                console.log(`Loading gifts attempt ${attempt}/${maxRetries}`);
                const success = await loadGifts();
                if (success) {
                    console.log('Gifts loaded successfully on attempt', attempt);
                    return true;
                }
                
                if (attempt < maxRetries) {
                    console.log(`Attempt ${attempt} failed, retrying in 2 seconds...`);
                    await new Promise(resolve => setTimeout(resolve, 2000));
                }
            }
            
            console.error('Failed to load gifts after', maxRetries, 'attempts');
            return false;
        }

        // Update current time display
        function updateTime() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const day = days[now.getDay()];
            const time = now.toLocaleTimeString('id-ID');
            
            document.getElementById('current-day').textContent = day;
            document.getElementById('current-time').textContent = time;
        }

        // Load gifts from server
        async function loadGifts() {
            try {
                console.log('Loading gifts from /api/gifts...'); // Debug log
                const response = await fetch('/api/gifts');
                
                if (response.ok) {
                    gifts = await response.json();
                    console.log('Gifts loaded successfully:', gifts); // Debug log
                    
                    // Ensure gifts is always an array
                    if (!Array.isArray(gifts)) {
                        gifts = [];
                        console.warn('Gifts response is not an array, setting to empty array');
                    }
                    
                    displayPrizeList();
                    return true; // Return success
                } else {
                    console.error('Failed to load gifts. Status:', response.status, response.statusText);
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    gifts = []; // Ensure gifts is empty array on failure
                    return false; // Return failure
                }
            } catch (error) {
                console.error('Error loading gifts:', error);
                gifts = []; // Ensure gifts is empty array on error
                // Display error message to user
                const prizeList = document.getElementById('prize-list');
                if (prizeList) {
                    prizeList.innerHTML = '<div class="prize-item"><span>Gagal memuat hadiah. Silakan refresh halaman.</span></div>';
                }
                return false; // Return failure
            }
        }

        // Display prize list with gift box animations
        function displayPrizeList() {
            const prizeList = document.getElementById('prize-list');
            prizeList.innerHTML = '';

            console.log('Displaying prizes with simple layout:', gifts); // Debug log

            if (!gifts || gifts.length === 0) {
                prizeList.innerHTML = '<div class="prize-item"><span>Belum ada hadiah tersedia</span></div>';
                return;
            }

            // Create simple prize items
            gifts.forEach((gift, index) => {
                console.log(`Processing gift ${index}:`, gift); // Debug log
                
                const prizeItem = document.createElement('div');
                prizeItem.className = 'prize-item';
                prizeItem.setAttribute('data-gift-index', index);
                
                // Construct image URL
                const imageUrl = `/storage/${gift.image_path}`;
                console.log(`Image URL for ${gift.nama_hadiah}:`, imageUrl); // Debug log
                
                // Create image element
                const img = document.createElement('img');
                img.src = imageUrl;
                img.alt = gift.nama_hadiah;
                
                img.onload = function() {
                    console.log(`✓ Successfully loaded image: ${this.src}`);
                };
                
                img.onerror = function() {
                    console.error(`✗ Failed to load image: ${this.src}`);
                    this.src = '/images/default-gift.png';
                    console.log(`Fallback to default image: ${this.src}`);
                };
                
                // Create text span
                const span = document.createElement('span');
                span.textContent = gift.nama_hadiah;
                
                // Add elements to prize item
                prizeItem.appendChild(img);
                prizeItem.appendChild(span);
                
                prizeList.appendChild(prizeItem);
            });
        }

        // Show ticket input form
        function showTicketForm() {
            const form = document.getElementById('ticket-form');
            const prizeListContainer = document.getElementById('prize-list-container');
            const giftContainer = document.getElementById('gift-container');
            
            // Hide other containers
            prizeListContainer.classList.remove('show');
            giftContainer.classList.remove('show', 'show-closed');
            
            // Show/hide form
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                document.getElementById('ticket-code').focus();
            } else {
                form.style.display = 'none';
            }
            
            // Clear previous notices
            document.getElementById('ticket-notice').style.display = 'none';
        }

        // Show prize list
        function showPrizeList() {
            const prizeListContainer = document.getElementById('prize-list-container');
            const form = document.getElementById('ticket-form');
            const giftContainer = document.getElementById('gift-container');
            
            // Hide other containers
            form.style.display = 'none';
            giftContainer.classList.remove('show', 'show-closed');
            
            // Toggle prize list
            if (prizeListContainer.classList.contains('show')) {
                prizeListContainer.classList.remove('show');
            } else {
                // Ensure gifts are loaded before showing
                if (!gifts || gifts.length === 0) {
                    console.log('Gifts not loaded, reloading...');
                    loadGifts().then(() => {
                        prizeListContainer.classList.add('show');
                    });
                } else {
                    prizeListContainer.classList.add('show');
                }
            }
        }

        // Show game rules modal
        function showGameRules() {
            document.getElementById('game-rules-modal').classList.add('show');
        }

        // Close game rules modal
        function closeGameRules() {
            document.getElementById('game-rules-modal').classList.remove('show');
        }

        // Validate ticket code
        async function validateTicket() {
            const ticketCode = document.getElementById('ticket-code').value.trim();
            const notice = document.getElementById('ticket-notice');
            
            if (!ticketCode) {
                showNotice('Silakan masukkan kode tiket!', 'error');
                return;
            }

            console.log('=== TICKET VALIDATION STARTED ===');
            console.log('Ticket code:', ticketCode);
            console.log('Request URL: /ticket/validate');

            try {
                console.log('Sending validation request...');
                
                const response = await fetch('/ticket/validate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ kode_tiket: ticketCode })
                });

                console.log('Validation response received:');
                console.log('- Status:', response.status);
                console.log('- Status Text:', response.statusText);
                console.log('- OK:', response.ok);
                console.log('- Headers:', [...response.headers.entries()]);

                if (!response.ok) {
                    console.error('Response not OK:', response.status, response.statusText);
                    
                    // Handle different error status codes
                    if (response.status === 400) {
                        // For 400 Bad Request (invalid ticket), show custom message
                        try {
                            const errorData = await response.json();
                            // Use the server's error message if available, otherwise use custom message
                            const errorMessage = errorData.message || 'Kode yang Anda masukkan salah, harap hubungi Admin untuk mendapatkan kode tiket.';
                            showNotice(errorMessage, 'error');
                        } catch (parseError) {
                            // If JSON parsing fails, show custom message
                            showNotice('Kode yang Anda masukkan salah, harap hubungi Admin untuk mendapatkan kode tiket.', 'error');
                        }
                    } else {
                        // For other errors, show generic server error message
                        const errorText = await response.text();
                        console.error('Error response text:', errorText);
                        showNotice(`Server error: ${response.status} ${response.statusText}`, 'error');
                    }
                    return;
                }

                const data = await response.json();
                console.log('Validation response data:', data);

                if (data.success) {
                    currentTicketCode = ticketCode;
                    currentTicketPrize = data.hadiah;
                    isTicketValidated = true;
                    
                    console.log('✅ Ticket validation successful:', data);
                    
                    // Store gift info for later use - store the gift name properly
                    if (typeof data.hadiah === 'object' && data.hadiah.nama_hadiah) {
                        localStorage.setItem('ticketHadiah', data.hadiah.nama_hadiah);
                        console.log('Stored gift name:', data.hadiah.nama_hadiah);
                    } else {
                        localStorage.setItem('ticketHadiah', data.hadiah);
                        console.log('Stored gift (fallback):', data.hadiah);
                    }
                    
                    showNotice('Tiket valid! Silakan pilih kotak hadiah.', 'success');
                    showGiftBoxes();
                    
                    // Hide form after successful validation
                    setTimeout(() => {
                        document.getElementById('ticket-form').style.display = 'none';
                    }, 2000);
                } else {
                    console.error('❌ Validation failed:', data);
                    showNotice(data.message || 'Kode tiket tidak valid!', 'error');
                    isTicketValidated = false;
                }
            } catch (error) {
                console.error('=== ERROR IN TICKET VALIDATION ===');
                console.error('Error type:', error.name);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                console.error('Full error object:', error);
                
                // More specific error messages based on error type
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    showNotice('Kesalahan jaringan. Periksa koneksi internet Anda.', 'error');
                } else if (error.name === 'SyntaxError') {
                    showNotice('Kesalahan format data dari server.', 'error');
                } else if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
                    showNotice('Tidak dapat terhubung ke server. Periksa koneksi internet.', 'error');
                } else {
                    showNotice(`Terjadi kesalahan validasi: ${error.message}`, 'error');
                }
            }
        }

        // Show notice message
        function showNotice(message, type) {
            const notice = document.getElementById('ticket-notice');
            
            // Create enhanced message with contact information for both success and error
            const socialMediaButtons = `
                @if($websiteSettings->whatsapp_number || $websiteSettings->telegram_number)
                <div style="color: #488c2c; font-size: 14px; margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(72, 140, 44, 0.2);">
                    <div style="margin-bottom: 12px; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fas fa-headset" style="font-size: 16px;"></i>
                        <span>Hubungi Admin</span>
                    </div>
                    <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                        @if($websiteSettings->whatsapp_number)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $websiteSettings->whatsapp_number) }}" 
                           target="_blank" 
                           class="social-media-btn whatsapp-btn"
                           title="Chat via WhatsApp">
                            <i class="fab fa-whatsapp" style="font-size: 16px;"></i>
                            <span>{{ $websiteSettings->whatsapp_number }}</span>
                        </a>
                        @endif
                        @if($websiteSettings->telegram_number)
                        <a href="{{ str_starts_with($websiteSettings->telegram_number, '@') ? 'https://t.me/' . substr($websiteSettings->telegram_number, 1) : 'https://t.me/' . preg_replace('/[^0-9]/', '', $websiteSettings->telegram_number) }}" 
                           target="_blank" 
                           class="social-media-btn telegram-btn"
                           title="Chat via Telegram">
                            <i class="fab fa-telegram" style="font-size: 16px;"></i>
                            <span>{{ $websiteSettings->telegram_number }}</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            `;
            
            // Create enhanced message with social media buttons
            notice.innerHTML = `
                <div style="text-align: center; margin-bottom: 15px;">
                    <div style="color: ${type === 'success' ? '#488c2c' : '#7a3d2a'}; font-weight: bold; margin-bottom: 10px;">
                        ${message}
                    </div>
                    ${socialMediaButtons}
                </div>
            `;
            
            notice.style.display = 'block';
            notice.style.color = type === 'success' ? '#488c2c' : '#7a3d2a';
            
            if (type === 'success') {
                setTimeout(() => {
                    notice.style.display = 'none';
                }, 5000); // Increased timeout for success messages so users can see social media buttons
            }
        }

        // Show gift boxes
        function showGiftBoxes() {
            console.log('=== SHOWING GIFT BOXES ===');
            console.log('isTicketValidated:', isTicketValidated);
            console.log('currentTicketCode:', currentTicketCode);
            console.log('gifts loaded:', gifts.length);
            
            // Ensure gifts are loaded before showing gift boxes
            if (!gifts || gifts.length === 0) {
                console.log('No gifts loaded, attempting to load gifts...');
                loadGifts().then(success => {
                    if (success && gifts.length > 0) {
                        console.log('Gifts loaded successfully, continuing with gift box creation');
                        showGiftBoxes(); // Recursively call after loading gifts
                    } else {
                        console.error('Failed to load gifts or no gifts available');
                        showNotice('Tidak ada hadiah tersedia. Silakan hubungi administrator untuk menambahkan hadiah.', 'warning');
                        return;
                    }
                });
                return;
            }
            
            const container = document.getElementById('gift-container');
            const prizeListContainer = document.getElementById('prize-list-container');
            
            if (!container) {
                console.error('Gift container not found!');
                return;
            }
            
            // Hide prize list
            prizeListContainer.classList.remove('show');
            
            // Clear existing boxes
            container.innerHTML = '';
            
            // Create 5 gift boxes
            for (let i = 0; i < 5; i++) {
                const giftBox = document.createElement('div');
                giftBox.className = 'gift-box closed';
                giftBox.id = `gift-box-${i}`;
                
                // Multiple event handlers for maximum compatibility
                giftBox.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    console.log(`=== GIFT BOX ${i} CLICKED (addEventListener) ===`);
                    console.log('Event target:', event.target);
                    console.log('isTicketValidated at click:', isTicketValidated);
                    
                    // Add immediate visual feedback
                    this.style.transform = 'scale(0.95)';
                    this.style.border = '4px solid #ff6b6b';
                    setTimeout(() => {
                        this.style.transform = '';
                        this.style.border = '4px solid #488c2c';
                    }, 200);
                    
                    openGiftBox(this, i).catch(error => {
                        console.error('Error in gift box handler:', error);
                    });
                }, true); // Use capture phase
                
                // Backup onclick handler
                giftBox.onclick = function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    console.log(`=== GIFT BOX ${i} CLICKED (onclick) ===`);
                    openGiftBox(this, i).catch(error => {
                        console.error('Error in gift box handler:', error);
                    });
                };
                
                // Touch events for mobile
                giftBox.addEventListener('touchstart', function(event) {
                    event.preventDefault();
                    console.log(`=== GIFT BOX ${i} TOUCHED ===`);
                    openGiftBox(this, i).catch(error => {
                        console.error('Error in gift box handler:', error);
                    });
                });
                
                // Add hover effect for better UX
                giftBox.addEventListener('mouseenter', function() {
                    console.log(`Gift box ${i} hovered`);
                    this.style.transform = 'scale(1.05)';
                    this.style.boxShadow = '0 0 30px rgba(72, 140, 44, 0.8)';
                });
                
                giftBox.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.boxShadow = '0 0 20px rgba(72, 140, 44, 0.4)';
                });
                
                // Add data attributes for debugging
                giftBox.setAttribute('data-box-index', i);
                giftBox.setAttribute('data-clickable', 'true');
                
                // Ensure the box is clickable with explicit styles
                giftBox.style.pointerEvents = 'auto';
                giftBox.style.cursor = 'pointer';
                giftBox.style.userSelect = 'none';
                giftBox.style.zIndex = '10';
                giftBox.style.position = 'relative';
                
                container.appendChild(giftBox);
                console.log(`Created gift box ${i} with enhanced click handlers`);
            }
            
            console.log('Created 5 gift boxes, showing container...');
            
            // Show container with animation
            container.classList.add('show-closed');
            
            // Add a test click handler to the container for debugging
            container.addEventListener('click', function(event) {
                console.log('Container clicked, target:', event.target);
                console.log('Target classes:', event.target.className);
            });
            
            console.log('=== GIFT BOXES SETUP COMPLETE ===');
        }

        // Open gift box
        async function openGiftBox(box, index) {
            console.log('=== GIFT BOX OPENING PROCESS STARTED ===');
            console.log('Gift box clicked:', box);
            console.log('Box index:', index);
            console.log('Box ID:', box.id);
            console.log('Box classes:', box.className);
            console.log('Current validation state:', isTicketValidated);
            console.log('Current ticket code:', currentTicketCode);
            console.log('localStorage ticketHadiah:', localStorage.getItem('ticketHadiah'));
            
            // Add immediate visual feedback
            box.style.border = '4px solid #ff6b6b';
            box.style.boxShadow = '0 0 40px rgba(255, 107, 107, 0.8)';
            setTimeout(() => {
                box.style.border = '4px solid #488c2c';
                box.style.boxShadow = '0 0 20px rgba(72, 140, 44, 0.4)';
            }, 500);
            
            if (!isTicketValidated) {
                console.log('❌ Ticket not validated, showing error');
                showNotice('Silakan validasi tiket terlebih dahulu!', 'error');
                return;
            }
            
            if (!currentTicketCode) {
                console.log('❌ No current ticket code');
                showNotice('Kode tiket tidak ditemukan. Silakan validasi ulang!', 'error');
                return;
            }

            if (box.classList.contains('opened')) {
                console.log('❌ Box already opened, returning');
                showNotice('Kotak hadiah sudah dibuka!', 'warning');
                return;
            }

            console.log('✅ All validations passed, processing gift box opening...');

            try {
                console.log('Sending claim request...');
                console.log('Request URL: /ticket/claim');
                console.log('Request method: POST');
                console.log('Request body:', JSON.stringify({ kode_tiket: currentTicketCode }));
                
                // Claim the ticket
                const response = await fetch('/ticket/claim', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ kode_tiket: currentTicketCode })
                });

                console.log('Claim response received:');
                console.log('- Status:', response.status);
                console.log('- Status Text:', response.statusText);
                console.log('- OK:', response.ok);
                
                if (!response.ok) {
                    console.error('Response not OK:', response.status, response.statusText);
                    const errorText = await response.text();
                    console.error('Error response text:', errorText);
                    showNotice(`Server error: ${response.status} ${response.statusText}`, 'error');
                    return;
                }
                
                const data = await response.json();
                console.log('Claim response data:', data);

                if (data.success) {
                    console.log('Claim successful, processing gift...');
                    // Get the predetermined gift from admin configuration (stored during ticket validation)
                    const adminConfiguredGift = localStorage.getItem('ticketHadiah');
                    
                    console.log('Admin configured gift:', adminConfiguredGift); // Debug log
                    console.log('Available gifts:', gifts); // Debug log
                    console.log('Gifts array length:', gifts.length); // Debug log
                    
                    // Find the gift object that matches the admin-configured gift
                    let selectedGift = null;
                    
                    if (adminConfiguredGift && gifts.length > 0) {
                        // Try to find exact match by name
                        selectedGift = gifts.find(gift => gift.nama_hadiah === adminConfiguredGift);
                        
                        // If not found, try case-insensitive match
                        if (!selectedGift) {
                            selectedGift = gifts.find(gift => 
                                gift.nama_hadiah.toLowerCase() === adminConfiguredGift.toLowerCase()
                            );
                        }
                        
                        // If still not found, try partial match
                        if (!selectedGift) {
                            selectedGift = gifts.find(gift => 
                                gift.nama_hadiah.toLowerCase().includes(adminConfiguredGift.toLowerCase()) ||
                                adminConfiguredGift.toLowerCase().includes(gift.nama_hadiah.toLowerCase())
                            );
                        }
                    }
                    
                    // Fallback to first gift if no match found and gifts exist
                    if (!selectedGift && gifts.length > 0) {
                        selectedGift = gifts[0];
                        console.warn('Gift not found, using first available gift:', selectedGift);
                    }
                    
                    // If gifts array is empty, try to reload gifts and use fallback
                    if (!selectedGift && gifts.length === 0) {
                        console.warn('Gifts array is empty, attempting to reload gifts...');
                        try {
                            const reloadSuccess = await loadGifts();
                            if (reloadSuccess && gifts.length > 0) {
                                selectedGift = gifts[0];
                                console.log('Gifts reloaded successfully, using first gift:', selectedGift);
                            }
                        } catch (reloadError) {
                            console.error('Failed to reload gifts:', reloadError);
                        }
                    }
                    
                    // Final fallback if still no gift found
                    if (!selectedGift) {
                        console.error('No gifts available after all attempts');
                        // Create a fallback gift object
                        selectedGift = {
                            nama_hadiah: adminConfiguredGift || 'Hadiah Spesial',
                            image_path: 'default-gift.png',
                            description: 'Hadiah dari event'
                        };
                        console.log('Using fallback gift object:', selectedGift);
                    }
                    
                    console.log('Selected gift for display:', selectedGift); // Debug log
                    
                    // Create sparkle effect
                    createSparkles(box);
                    
                    // Open the box
                    box.classList.remove('closed');
                    box.classList.add('opened');
                    
                    // Add gift image
                    const img = document.createElement('img');
                    img.src = `/storage/${selectedGift.image_path}`;
                    img.alt = selectedGift.nama_hadiah;
                    img.onerror = function() {
                        this.src = '/images/default-gift.png';
                    };
                    box.appendChild(img);
                    
                    // Show congratulations after animation
                    setTimeout(() => {
                        showCongratulations(selectedGift);
                    }, 1500);
                    
                    // Disable other boxes
                    const allBoxes = document.querySelectorAll('.gift-box');
                    allBoxes.forEach(otherBox => {
                        if (otherBox !== box) {
                            otherBox.style.pointerEvents = 'none';
                            otherBox.style.opacity = '0.5';
                        }
                    });
                    
                    // Clear stored gift info
                    localStorage.removeItem('ticketHadiah');
                    
                } else {
                    console.error('Claim failed:', data);
                    showNotice(data.message || 'Gagal mengklaim hadiah!', 'error');
                }
            } catch (error) {
                console.error('=== ERROR IN GIFT BOX OPENING ===');
                console.error('Error type:', error.name);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                console.error('Current state:');
                console.error('- isTicketValidated:', isTicketValidated);
                console.error('- currentTicketCode:', currentTicketCode);
                console.error('- localStorage ticketHadiah:', localStorage.getItem('ticketHadiah'));
                console.error('Full error object:', error);
                
                // More specific error messages based on error type
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    showNotice('Kesalahan jaringan. Periksa koneksi internet Anda.', 'error');
                } else if (error.name === 'SyntaxError') {
                    showNotice('Kesalahan format data dari server.', 'error');
                } else {
                    showNotice(`Terjadi kesalahan: ${error.message}`, 'error');
                }
            }
        }

        // Create sparkle effects
        function createSparkles(box) {
            const rect = box.getBoundingClientRect();
            const containerRect = box.parentElement.getBoundingClientRect();
            
            for (let i = 0; i < 10; i++) {
                const sparkle = document.createElement('div');
                sparkle.className = 'sparkle';
                sparkle.style.left = (rect.left - containerRect.left + Math.random() * rect.width) + 'px';
                sparkle.style.top = (rect.top - containerRect.top + Math.random() * rect.height) + 'px';
                sparkle.style.animationDelay = (Math.random() * 0.5) + 's';
                
                box.parentElement.appendChild(sparkle);
                
                // Remove sparkle after animation
                setTimeout(() => {
                    if (sparkle.parentElement) {
                        sparkle.parentElement.removeChild(sparkle);
                    }
                }, 1500);
            }
        }

        // Show congratulations modal
        function showCongratulations(gift) {
            const modal = document.getElementById('congratulations-message');
            const prizeImage = document.getElementById('won-prize-image');
            const prizeName = document.getElementById('won-prize-name');
            
            prizeImage.src = `/storage/${gift.image_path}`;
            prizeImage.alt = gift.nama_hadiah;
            prizeImage.onerror = function() {
                this.src = '/images/default-gift.png';
            };
            prizeName.textContent = gift.nama_hadiah;
            
            modal.classList.add('show');
        }

        // Close congratulations modal
        function closeCongratulations() {
            const modal = document.getElementById('congratulations-message');
            modal.classList.remove('show');
            
            // Reset the game
            resetGame();
        }

        // Reset game state
        function resetGame() {
            currentTicketCode = '';
            currentTicketPrize = '';
            isTicketValidated = false;
            
            // Clear form
            document.getElementById('ticket-code').value = '';
            document.getElementById('ticket-notice').style.display = 'none';
            document.getElementById('ticket-form').style.display = 'none';
            
            // Hide containers
            document.getElementById('gift-container').classList.remove('show', 'show-closed');
            document.getElementById('prize-list-container').classList.remove('show');
            
            // Clear gift container
            document.getElementById('gift-container').innerHTML = '';
        }

        // Allow Enter key to submit ticket
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && document.getElementById('ticket-form').style.display === 'block') {
                validateTicket();
            }
        });

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            const gameRulesModal = document.getElementById('game-rules-modal');
            const congratsModal = document.getElementById('congratulations-message');
            
            if (e.target === gameRulesModal) {
                closeGameRules();
            }
            
            if (e.target === congratsModal) {
                closeCongratulations();
            }
        });

    </script>
</body>
</html>