<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHESS SUPREME</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        :root {
            --gold: #d4af37;
            --gold-light: #f7e08a;
            --gold-dark: #aa8d28;
            --bg-dark: #0d0d0d;
            --bg-glass: rgba(20, 20, 20, 0.7);
            --border-gold: rgba(212, 175, 55, 0.3);
            --text-light: #ffffff;
            --text-muted: #a0a0a0;
            --square-light: #f0d9b5;
            --square-dark: #b58863;
            --highlight: rgba(255, 255, 100, 0.5);
            --valid-move: rgba(0, 0, 0, 0.15);
            --capture: rgba(255, 0, 0, 0.4);
        }

        .dark-theme {
            --square-light: #2c2c2c;
            --square-dark: #1a1a1a;
        }

        .dark-theme .piece {
            transition: all 0.3s ease;
        }

        .dark-theme .piece.white {
            color: #fff;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.6),
                0 0 20px rgba(212, 175, 55, 0.3),
                1px 1px 2px #000;
            filter: drop-shadow(0 0 2px rgba(212, 175, 55, 0.5));
        }

        .dark-theme .piece.black {
            color: #0f0f0f;
            text-shadow: 0 0 8px rgba(212, 175, 55, 0.5),
                0 0 15px rgba(212, 175, 55, 0.2);
            filter: drop-shadow(0 0 1px rgba(212, 175, 55, 0.3));
            opacity: 0.9;
        }

        .dark-theme .square.valid-move::after {
            background: rgba(212, 175, 55, 0.6);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.4);
        }

        .emerald-theme {
            --square-light: #e9edcc;
            --square-dark: #779556;
        }

        .wood-theme {
            --square-light: #deb887;
            --square-dark: #8b4513;
        }

        .classic-theme {
            --square-light: #dee3e6;
            --square-dark: #8ca2ad;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #050505;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-light);
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* Premium Background Effects */
        .bg-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 50% 50%, #0d0d0d 0%, #050505 100%);
            overflow: hidden;
        }

        .blob {
            position: absolute;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.25) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(100px);
            animation: move 20s infinite alternate;
        }

        .blob-1 {
            top: -200px;
            right: -100px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.2) 0%, transparent 70%);
        }

        .blob-2 {
            bottom: -200px;
            left: -100px;
            background: radial-gradient(circle, rgba(184, 150, 12, 0.15) 0%, transparent 70%);
            animation-duration: 25s;
            animation-delay: -5s;
        }

        .blob-3 {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 1000px;
            height: 1000px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.05) 0%, transparent 70%);
            animation: pulse-bg 15s infinite alternate;
        }

        @keyframes move {
            0% {
                transform: translate(0, 0) scale(1) rotate(0deg);
            }

            33% {
                transform: translate(-150px, 100px) scale(1.1) rotate(10deg);
            }

            66% {
                transform: translate(100px, -50px) scale(0.9) rotate(-10deg);
            }

            100% {
                transform: translate(0, 0) scale(1) rotate(0deg);
            }
        }

        @keyframes pulse-bg {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.3;
                filter: hue-rotate(0deg);
            }

            50% {
                transform: translate(-50%, -50%) scale(1.1);
                opacity: 0.5;
                filter: hue-rotate(15deg);
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.3;
                filter: hue-rotate(0deg);
            }
        }

        .noise-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.05;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3e%3cfilter id='noiseFilter'%3e%3cfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3e%3c/filter%3e%3crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3e%3c/svg%3e");
        }

        .vignette {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle, transparent 40%, rgba(0, 0, 0, 0.8) 100%);
            pointer-events: none;
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            color: var(--gold);
            opacity: 0;
            font-family: 'Cinzel', serif;
            filter: drop-shadow(0 0 5px rgba(212, 175, 55, 0.4));
            animation: drift var(--duration) linear infinite;
            animation-delay: var(--delay);
            user-select: none;
            pointer-events: none;
        }

        @keyframes drift {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg);
                opacity: 0;
            }

            20% {
                opacity: 0.3;
            }

            80% {
                opacity: 0.3;
            }

            100% {
                transform: translateY(-100vh) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }

        .game-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
            align-items: center;
            max-width: 1400px;
            width: 95%;
            padding: 20px;
        }

        .game-top-section {
            display: flex;
            flex-direction: row;
            gap: 40px;
            align-items: flex-start;
            justify-content: center;
            width: 100%;
        }

        .game-bottom-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            width: 100%;
            max-width: 1100px;
            /* Match board + side panel visual width */
        }

        @media (max-width: 1024px) {
            .game-top-section {
                flex-direction: column;
                align-items: center;
            }

            .game-bottom-section {
                grid-template-columns: 1fr;
            }
        }

        .board-wrapper {
            margin-top: 140px;
            /* Push board down as requested */
            position: relative;
            z-index: 2;
        }

        .board-frame {
            padding: 12px;
            background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
            border-radius: 8px;
            border: 2px solid var(--border-gold);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8),
                0 0 50px rgba(212, 175, 55, 0.15),
                inset 0 0 2px rgba(212, 175, 55, 0.5);
        }

        .chess-board {
            display: grid;
            grid-template-columns: repeat(8, 65px);
            grid-template-rows: repeat(8, 65px);
            border-radius: 4px;
            overflow: hidden;
        }

        .square {
            width: 65px;
            height: 65px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3rem;
            cursor: pointer;
            position: relative;
            transition: all 0.15s ease;
        }

        .square.light {
            background-color: var(--square-light);
        }

        .square.dark {
            background-color: var(--square-dark);
        }

        .square:hover {
            filter: brightness(1.1);
        }

        .square.selected {
            background: var(--highlight) !important;
            box-shadow: inset 0 0 0 3px var(--gold);
        }

        .square.last-move {
            background: rgba(212, 175, 55, 0.25) !important;
        }

        .square.valid-move::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: var(--valid-move);
            border-radius: 50%;
            pointer-events: none;
        }

        .square.capture-move::after {
            content: '';
            position: absolute;
            width: 55px;
            height: 55px;
            border: 4px solid var(--capture);
            border-radius: 50%;
            pointer-events: none;
        }

        .piece {
            user-select: none;
            cursor: grab;
            line-height: 1;
            filter: drop-shadow(2px 2px 2px rgba(0, 0, 0, 0.3));
        }

        .piece:active {
            cursor: grabbing;
            transform: scale(1.1);
        }

        .piece.white {
            color: #fff;
            text-shadow: 1px 1px 0 #000, -1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000;
        }

        .piece.black {
            color: #1a1a1a;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.2);
        }

        .side-panel {
            width: 350px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            flex-shrink: 0;
        }

        .info-card-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            height: 100%;
        }

        .reset-btn-container {
            grid-column: span 3;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 1024px) {
            .side-panel {
                width: 100%;
                max-width: 600px;
            }

            .reset-btn-container {
                grid-column: span 1;
            }
        }

        /* Reset Grid Spans */
        .controls-card {
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: rgba(255, 255, 255, 0.02);
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .controls-card>div {
            width: 100%;
            min-width: 0;
        }

        .reset-btn-container {
            width: 100% !important;
        }

        /* Adjustments for side panel density */
        .move-history {
            height: 200px;
        }

        .status-card {
            padding: 15px;
        }


        .logo {
            text-align: center;
        }

        .logo .crown {
            font-size: 3rem;
            display: block;
            margin-bottom: 5px;
            background: linear-gradient(180deg, var(--gold-light), var(--gold-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo h1 {
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 4px;
            background: linear-gradient(180deg, var(--gold-light), var(--gold));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2px;
        }

        .logo p {
            font-family: 'Outfit', sans-serif;
            font-size: 0.8rem;
            color: var(--text-muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: -5px;
            margin-bottom: 10px;
        }

        .difficulty-selector,
        .theme-selector {
            display: flex;
            gap: 10px;
            background: var(--bg-glass);
            border: 1px solid var(--border-gold);
            border-radius: 12px;
            padding: 8px;
        }

        .theme-selector {
            margin-top: 10px;
            display: flex;
            justify-content: space-around;
        }

        .theme-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.2s;
        }

        .theme-dot:hover {
            transform: scale(1.2);
        }

        .theme-dot.active {
            border-color: var(--gold);
        }

        .theme-emerald {
            background: #2ecc71;
        }

        .theme-wood {
            background: #a67c52;
        }

        .theme-classic {
            background: #3498db;
        }

        .theme-dark {
            background: #2c3e50;
        }

        .difficulty-selector {
            margin-top: 10px;
            display: none;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .difficulty-selector.show {
            display: grid;
        }

        .diff-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 6px;
            border: 1px solid rgba(212, 175, 55, 0.1);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-muted);
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .diff-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-light);
            border-color: rgba(212, 175, 55, 0.3);
            transform: translateY(-1px);
        }

        .diff-btn.active {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.25), rgba(184, 150, 12, 0.1));
            border-color: var(--gold);
            color: var(--gold-light);
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3), 0 0 10px rgba(212, 175, 55, 0.2);
            transform: translateY(-2px);
        }

        .diff-btn.active::after,
        .mode-btn.active::after,
        .color-btn.active::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: var(--gold);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--gold);
        }

        .hint-btn {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--gold);
            color: var(--gold);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
            justify-content: center;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }

        .hint-btn:hover {
            background: var(--gold);
            color: black;
        }

        .move-history {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(212, 175, 55, 0.15);
            border-radius: 12px;
            padding: 16px;
            height: 250px;
            /* Increased for dashboard view */
            overflow-y: auto;
            font-size: 0.85rem;
            color: var(--text-muted);
            box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
        }

        .move-history h3 {
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: var(--gold-light);
            letter-spacing: 2px;
            margin-bottom: 12px;
            position: sticky;
            top: 0;
            background: rgba(13, 13, 13, 0.95);
            padding: 4px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }

        .move-history h3::before {
            content: '📜';
            font-size: 0.9rem;
        }

        #history-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .history-item {
            padding: 4px 8px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.01);
            transition: all 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-item:hover {
            background: rgba(212, 175, 55, 0.05);
            color: var(--text-light);
        }

        .history-item .move-idx {
            font-family: 'Cinzel', serif;
            color: var(--gold);
            font-size: 0.75rem;
            opacity: 0.7;
        }

        .activity-feed {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            padding: 12px;
            height: 100%;
            border: 1px dashed rgba(212, 175, 55, 0.3);
        }

        .activity-feed h3 {
            font-size: 0.7rem;
            color: var(--gold-light);
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .feed-item {
            font-size: 0.75rem;
            color: #ccc;
            margin-bottom: 4px;
            animation: slideInLeft 0.5s ease-out;
            display: flex;
            gap: 5px;
        }

        .user {
            color: var(--gold);
            font-weight: 600;
        }

        .user.special-user {
            background: linear-gradient(to right, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #9400d3);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            /* Fallback */
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            background-size: 200% auto;
            animation: rainbow 3s linear infinite;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
        }

        @keyframes rainbow {
            to {
                background-position: 200% center;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .streak-counter {
            background: linear-gradient(135deg, #ff4e50, #f9d423);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .mode-selector,
        .color-selector {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }

        .mode-selector {
            display: flex;
            /* Always show mode selector */
        }

        .color-selector {
            display: none;
        }

        .color-selector.show {
            display: flex;
        }

        .mode-btn,
        .color-btn {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 16px 8px;
            border: 1px solid rgba(212, 175, 55, 0.1);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-muted);
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .mode-btn .icon,
        .color-btn .icon {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .mode-btn:hover,
        .color-btn:hover {
            color: var(--text-light);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(212, 175, 55, 0.3);
            transform: translateY(-2px);
        }

        .mode-btn:hover .icon,
        .color-btn:hover .icon {
            transform: scale(1.1);
        }

        .mode-btn.active,
        .color-btn.active {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.25), rgba(184, 150, 12, 0.15));
            border-color: var(--gold);
            color: var(--gold-light);
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4), 0 0 15px rgba(212, 175, 55, 0.25);
            transform: translateY(-3px);
        }

        .mode-btn.active .icon,
        .color-btn.active .icon {
            transform: scale(1.2);
            filter: drop-shadow(0 0 8px var(--gold));
        }

        .status-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-gold);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .turn-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .gem {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .gem.white {
            background: radial-gradient(circle at 30% 30%, #fff, #ccc);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        .gem.black {
            background: radial-gradient(circle at 30% 30%, #444, #000);
            border: 1px solid #555;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .thinking {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            color: var(--gold);
            font-size: 0.9rem;
            animation: fadeIn 0.3s ease;
        }

        .thinking.show {
            display: flex;
        }

        .loader {
            width: 18px;
            height: 18px;
            border: 2px solid var(--gold);
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            animation: spin 1s linear infinite;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .captured-section {
            background: var(--bg-glass);
            border: 1px solid var(--border-gold);
            border-radius: 12px;
            padding: 16px;
            height: 100%;
        }

        .captured-section h3 {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .captured-box {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .captured-row {
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 28px;
        }

        .captured-row .label {
            font-size: 0.8rem;
            color: var(--text-muted);
            width: 50px;
        }

        .captured-row .pieces {
            font-size: 1.4rem;
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
        }

        .white-captured .pieces {
            color: #fff;
            text-shadow: 0 0 3px #000;
        }

        .black-captured .pieces {
            color: #222;
            text-shadow: 0 0 2px rgba(255, 255, 255, 0.3);
        }

        .reset-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #000;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }

        .reset-btn .btn-icon {
            font-size: 1.2rem;
        }

        @media (max-width: 900px) {
            .game-container {
                flex-direction: column;
                gap: 24px;
            }

            .chess-board {
                grid-template-columns: repeat(8, calc((100vw - 80px)/8));
                grid-template-rows: repeat(8, calc((100vw - 80px)/8));
            }

            .square {
                width: calc((100vw - 80px)/8);
                height: calc((100vw - 80px)/8);
                font-size: clamp(1.8rem, 5vw, 3rem);
            }

            .info-panel {
                width: 100%;
                max-width: 400px;
            }
        }
    </style>
</head>

<body>
    <div class="bg-gradient">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>
    <div class="noise-overlay"></div>
    <div class="vignette"></div>
    <div class="particles" id="particles"></div>
    <div class="game-container">
        <!-- Top Section: Board + Side Panel -->
        <div class="game-top-section">
            <div class="board-wrapper">
                <div class="board-frame">
                    <div id="chess-board" class="chess-board"></div>
                </div>
            </div>

            <div class="side-panel">
                <!-- Controls Row -->
                <div class="controls-card">
                    <div class="logo">
                        <span class="crown">👑</span>
                        <h1>CHESS SUPREME</h1>
                        <p>created by zuumar</p>
                    </div>
                    <div class="mode-selector">
                        <button class="mode-btn active" id="bot-mode">
                            <span class="icon">🤖</span><span>vs AI</span>
                        </button>
                        <button class="mode-btn" id="pvp-mode">
                            <span class="icon">👥</span><span>2 Players</span>
                        </button>
                    </div>
                    <div class="color-selector show" id="color-selector">
                        <button class="color-btn active" id="choose-white">
                            <span class="icon">⚪</span><span>White</span>
                        </button>
                        <button class="color-btn" id="choose-black">
                            <span class="icon">⚫</span><span>Black</span>
                        </button>
                    </div>
                    <div class="difficulty-selector show" id="difficulty-selector">
                        <button class="diff-btn" id="diff-easy" data-level="easy">
                            <span>🌱 Easy</span>
                        </button>
                        <button class="diff-btn active" id="diff-medium" data-level="medium">
                            <span>⚔️ Medium</span>
                        </button>
                        <button class="diff-btn" id="diff-hard" data-level="hard">
                            <span>🔥 Hard</span>
                        </button>
                        <button class="diff-btn" id="diff-dewa" data-level="dewa">
                            <span>⚡ Dewa</span>
                        </button>
                    </div>
                    <div class="theme-selector" id="theme-selector">
                        <div class="theme-dot theme-emerald active" data-theme="emerald" title="Emerald"></div>
                        <div class="theme-dot theme-wood" data-theme="wood" title="Classic Wood"></div>
                        <div class="theme-dot theme-classic" data-theme="classic" title="Ocean Blue"></div>
                        <div class="theme-dot theme-dark" data-theme="dark" title="Midnight"></div>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="status-card">
                    <div id="streak-display" class="streak-counter">Win Streak: 0 🔥</div>
                    <div class="turn-indicator">
                        <div id="turn-gem" class="gem white"></div>
                        <span id="status">White's Turn</span>
                    </div>
                    <div id="thinking" class="thinking">
                        <div class="loader"></div><span>AI is calculating...</span>
                    </div>
                    <button id="hint-btn" class="hint-btn">
                        <span>💡</span> Get Hint
                    </button>
                    <div id="game-quote" class="quote-container"></div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: History, Stats, Etc -->
        <div class="game-bottom-section">
            <div class="move-history" id="move-history">
                <h3>Move History</h3>
                <div id="history-list"></div>
            </div>

            <div class="captured-section">
                <h3>Captured Pieces</h3>
                <div class="captured-box">
                    <div class="captured-row white-captured">
                        <span class="label">White:</span>
                        <span id="white-captured" class="pieces"></span>
                    </div>
                    <div class="captured-row black-captured">
                        <span class="label">Black:</span>
                        <span id="black-captured" class="pieces"></span>
                    </div>
                </div>
            </div>

            <div class="activity-feed">
                <h3><span class="dot"
                        style="width:8px;height:8px;background:#ff4e50;border-radius:50%;display:inline-block;animation:pulse 1s infinite"></span>
                    Global Activity</h3>
                <div id="feed-list">
                    <div class="feed-item"><span class="user">April Ngaceng</span> just won on Dewa mode!</div>
                    <div class="feed-item"><span class="user">Aldi Jomok</span> promoted a pawn to Queen!</div>
                    <div class="feed-item"><span class="user">Rafihuyy</span> achieved a 5-win streak!</div>
                    <div class="feed-item"><span class="user special-user">Zuumar</span> found a brilliant checkmate!
                    </div>
                    <div class="feed-item"><span class="user">Deo Kun</span> executed a perfect trap!</div>
                </div>
            </div>

            <div class="reset-btn-container">
                <button id="reset-btn" class="reset-btn" style="width: 100%; max-width: 400px;">
                    <span class="btn-icon">↻</span> New Game
                </button>
            </div>
        </div>
    </div>

    <script>
        const INITIAL_BOARD = [
            ['br', 'bn', 'bb', 'bq', 'bk', 'bb', 'bn', 'br'],
            ['bp', 'bp', 'bp', 'bp', 'bp', 'bp', 'bp', 'bp'],
            [null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null],
            ['wp', 'wp', 'wp', 'wp', 'wp', 'wp', 'wp', 'wp'],
            ['wr', 'wn', 'wb', 'wq', 'wk', 'wb', 'wn', 'wr']
        ];
        const UNICODE = { wp: '♙', wr: '♖', wn: '♘', wb: '♗', wq: '♕', wk: '♔', bp: '♟', br: '♜', bn: '♞', bb: '♝', bq: '♛', bk: '♚' };

        const WIN_QUOTES = [
            "Insane! You're a natural!",
            "Ezzzzzzz... maybe take a nap, kid.",
            "Even the bot lost, let alone my heart.",
            "CHESS SUPREME acknowledges your greatness.",
            "Eternal victory for the master of strategy!",
            "Perfect checkmate! The world bows to you.",
            "Brilliant move, undeniable dominance.",
            "This crown was truly made for you, Boss!"
        ];

        const LOSS_QUOTES = [
            "A bit weak, losing to a bot.",
            "LMAO maybe play marbles instead.",
            "The skill issue is real, my guy.",
            "Don't cry if you lose, okay kid?",
            "Repeat until you win, or just give up?",
            "Analyze your defeat, seize victory tomorrow.",
            "The King has fallen, but the Kingdom remains. Strike back!",
            "Don't give up, Boss! Even Grandmasters have bad days."
        ];

        const PIECE_VALUES = { p: 100, n: 320, b: 330, r: 500, q: 900, k: 20000 };
        const PAWN_TABLE = [[0, 0, 0, 0, 0, 0, 0, 0], [50, 50, 50, 50, 50, 50, 50, 50], [10, 10, 20, 30, 30, 20, 10, 10], [5, 5, 10, 25, 25, 10, 5, 5], [0, 0, 0, 20, 20, 0, 0, 0], [5, -5, -10, 0, 0, -10, -5, 5], [5, 10, 10, -20, -20, 10, 10, 5], [0, 0, 0, 0, 0, 0, 0, 0]];
        const KNIGHT_TABLE = [[-50, -40, -30, -30, -30, -30, -40, -50], [-40, -20, 0, 0, 0, 0, -20, -40], [-30, 0, 10, 15, 15, 10, 0, -30], [-30, 5, 15, 20, 20, 15, 5, -30], [-30, 0, 15, 20, 20, 15, 0, -30], [-30, 5, 10, 15, 15, 10, 5, -30], [-40, -20, 0, 5, 5, 0, -20, -40], [-50, -40, -30, -30, -30, -30, -40, -50]];
        const BISHOP_TABLE = [[-20, -10, -10, -10, -10, -10, -10, -20], [-10, 0, 0, 0, 0, 0, 0, -10], [-10, 0, 5, 10, 10, 5, 0, -10], [-10, 5, 5, 10, 10, 5, 5, -10], [-10, 0, 10, 10, 10, 10, 0, -10], [-10, 10, 10, 10, 10, 10, 10, -10], [-10, 5, 0, 0, 0, 0, 5, -10], [-20, -10, -10, -10, -10, -10, -10, -20]];
        const ROOK_TABLE = [[0, 0, 0, 0, 0, 0, 0, 0], [5, 10, 10, 10, 10, 10, 10, 5], [-5, 0, 0, 0, 0, 0, 0, -5], [-5, 0, 0, 0, 0, 0, 0, -5], [-5, 0, 0, 0, 0, 0, 0, -5], [-5, 0, 0, 0, 0, 0, 0, -5], [-5, 0, 0, 0, 0, 0, 0, -5], [0, 0, 0, 5, 5, 0, 0, 0]];
        const QUEEN_TABLE = [[-20, -10, -10, -5, -5, -10, -10, -20], [-10, 0, 0, 0, 0, 0, 0, -10], [-10, 0, 5, 5, 5, 5, 0, -10], [-5, 0, 5, 5, 5, 5, 0, -5], [0, 0, 5, 5, 5, 5, 0, -5], [-10, 5, 5, 5, 5, 5, 0, -10], [-10, 0, 5, 0, 0, 0, 0, -10], [-20, -10, -10, -5, -5, -10, -10, -20]];
        const KING_TABLE_MID = [[-30, -40, -40, -50, -50, -40, -40, -30], [-30, -40, -40, -50, -50, -40, -40, -30], [-30, -40, -40, -50, -50, -40, -40, -30], [-30, -40, -40, -50, -50, -40, -40, -30], [-20, -30, -30, -40, -40, -30, -30, -20], [-10, -20, -20, -20, -20, -20, -20, -10], [20, 20, 0, 0, 0, 0, 20, 20], [20, 30, 10, 0, 0, 10, 30, 20]];

        let state = {
            board: JSON.parse(JSON.stringify(INITIAL_BOARD)),
            turn: 'w', selectedSquare: null, validMoves: [], captured: { w: [], b: [] },
            lastMove: null, vsBot: true, botThinking: false, gameOver: false, winner: null,
            score: { white: 0, black: 0 }, playerColor: 'w', difficulty: 'medium',
            streak: parseInt(localStorage.getItem('chess_streak') || '0'),
            history: []
        };

        const boardEl = document.getElementById('chess-board');
        const statusEl = document.getElementById('status');
        const turnGem = document.getElementById('turn-gem');
        const thinkingEl = document.getElementById('thinking');
        const whiteCapturedEl = document.getElementById('white-captured');
        const blackCapturedEl = document.getElementById('black-captured');
        const streakDisplay = document.getElementById('streak-display');
        const historyList = document.getElementById('history-list');
        const feedList = document.getElementById('feed-list');

        function updateStreak(win) {
            if (win) state.streak++;
            else state.streak = 0;
            localStorage.setItem('chess_streak', state.streak);
            streakDisplay.textContent = `Win Streak: ${state.streak} 🔥`;
        }

        // Themes
        document.querySelectorAll('.theme-dot').forEach(dot => {
            dot.addEventListener('click', () => {
                document.querySelectorAll('.theme-dot').forEach(d => d.classList.remove('active'));
                dot.classList.add('active');
                const theme = dot.dataset.theme;
                document.body.className = theme + '-theme';
            });
        });

        // Mock Activity Feed
        const USERS = ["April Ngaceng", "Aldi Jomok", "Rafihuyy", "Zuumar", "Deo Kun", "Nopal Pedo"];
        const ACTIONS = ["just won on Dewa mode!", "achieved a 5-win streak!", "promoted a pawn to Queen!", "executed a perfect trap!", "found a brilliant checkmate!"];
        setInterval(() => {
            const user = USERS[Math.floor(Math.random() * USERS.length)];
            const action = ACTIONS[Math.floor(Math.random() * ACTIONS.length)];
            const item = document.createElement('div');
            item.className = 'feed-item';
            const userClass = user === 'Zuumar' ? 'user special-user' : 'user';
            item.innerHTML = `<span class="${userClass}">${user}</span> ${action}`;
            feedList.prepend(item);
            if (feedList.children.length > 5) feedList.lastElementChild.remove();
        }, 8000);

        // Hint System
        document.getElementById('hint-btn').addEventListener('click', () => {
            if (state.turn !== state.playerColor || state.botThinking || state.gameOver) return;
            const moves = getAllMoves(state.board, state.turn);
            if (moves.length === 0) return;

            // Use bot logic to find best move - Increased depth for winning strategy
            const best = findBestMove(state.board, state.turn, 4);
            if (best) {
                const sqs = document.querySelectorAll('.square');
                const isFlipped = state.vsBot && state.playerColor === 'b';

                // Highlight hint
                const fromIdx = (isFlipped ? 7 - best.from.row : best.from.row) * 8 + (isFlipped ? 7 - best.from.col : best.from.col);
                const toIdx = (isFlipped ? 7 - best.to.row : best.to.row) * 8 + (isFlipped ? 7 - best.to.col : best.to.col);

                sqs[fromIdx].style.boxShadow = "inset 0 0 20px #2ecc71";
                sqs[toIdx].style.boxShadow = "inset 0 0 20px #2ecc71";
                setTimeout(() => {
                    sqs[fromIdx].style.boxShadow = "";
                    sqs[toIdx].style.boxShadow = "";
                }, 1500);
            }
        });

        function findBestMove(board, color, depth) {
            const moves = getAllMoves(board, color);
            let bestMove = null;
            let bestScore = color === 'b' ? -Infinity : Infinity;
            const startTime = Date.now();

            for (const move of moves) {
                const nextBoard = applyMove(board, move);
                const score = minimax(nextBoard, depth - 1, -Infinity, Infinity, color === 'w', startTime);
                if (color === 'b') {
                    if (score > bestScore) { bestScore = score; bestMove = move; }
                } else {
                    if (score < bestScore) { bestScore = score; bestMove = move; }
                }
            }
            return bestMove;
        }

        document.getElementById('bot-mode').addEventListener('click', () => {
            state.vsBot = true;
            document.getElementById('bot-mode').classList.add('active');
            document.getElementById('pvp-mode').classList.remove('active');
            document.getElementById('color-selector').classList.add('show');
            resetGame();
        });
        document.getElementById('pvp-mode').addEventListener('click', () => {
            state.vsBot = false;
            document.getElementById('pvp-mode').classList.add('active');
            document.getElementById('bot-mode').classList.remove('active');
            document.getElementById('color-selector').classList.remove('show');
            resetGame();
        });
        document.getElementById('choose-white').addEventListener('click', () => {
            state.playerColor = 'w';
            document.getElementById('choose-white').classList.add('active');
            document.getElementById('choose-black').classList.remove('active');
            resetGame();
        });
        document.getElementById('choose-black').addEventListener('click', () => {
            state.playerColor = 'b';
            document.getElementById('choose-black').classList.add('active');
            document.getElementById('choose-white').classList.remove('active');
            resetGame();
        });

        // Difficulty Listeners
        ['easy', 'medium', 'hard', 'dewa'].forEach(level => {
            document.getElementById(`diff-${level}`).addEventListener('click', () => {
                state.difficulty = level;
                document.querySelectorAll('.diff-btn').forEach(btn => btn.classList.remove('active'));
                document.getElementById(`diff-${level}`).classList.add('active');
                if (state.vsBot && state.turn !== state.playerColor && !state.gameOver) {
                    thinkingEl.classList.add('show');
                    setTimeout(makeBotMove, 250);
                }
            });
        });
        document.getElementById('reset-btn').addEventListener('click', resetGame);

        function switchToPvP() {
            state.vsBot = false;
            document.getElementById('pvp-mode').classList.add('active');
            document.getElementById('bot-mode').classList.remove('active');
            document.getElementById('color-selector').classList.remove('show');
            resetGame();
        }

        let audioCtx = null;
        function playSound(type) {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = type === 'move' ? 'sine' : 'square';
                osc.frequency.setValueAtTime(type === 'move' ? 600 : 200, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(type === 'move' ? 400 : 100, audioCtx.currentTime + 0.08);
                gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.1);
                osc.connect(gain); gain.connect(audioCtx.destination);
                osc.start(); osc.stop(audioCtx.currentTime + 0.1);
            } catch (e) { }
        }

        function renderBoard() {
            boardEl.innerHTML = '';
            const isFlipped = state.vsBot && state.playerColor === 'b';
            for (let r = 0; r < 8; r++) {
                for (let c = 0; c < 8; c++) {
                    const row = isFlipped ? 7 - r : r;
                    const col = isFlipped ? 7 - c : c;
                    const piece = state.board[row][col];
                    const square = document.createElement('div');
                    square.className = `square ${(row + col) % 2 === 1 ? 'dark' : 'light'}`;
                    if (state.lastMove) {
                        const { from, to } = state.lastMove;
                        if ((from.row === row && from.col === col) || (to.row === row && to.col === col)) square.classList.add('last-move');
                    }
                    if (state.selectedSquare && state.selectedSquare.row === row && state.selectedSquare.col === col) square.classList.add('selected');
                    if (state.validMoves.find(m => m.row === row && m.col === col)) square.classList.add(piece ? 'capture-move' : 'valid-move');
                    if (piece) {
                        const pieceEl = document.createElement('span');
                        pieceEl.className = `piece ${piece[0] === 'w' ? 'white' : 'black'}`;
                        pieceEl.textContent = UNICODE[piece];
                        pieceEl.draggable = true;
                        pieceEl.addEventListener('dragstart', e => handleDragStart(e, row, col));
                        pieceEl.addEventListener('dragend', e => e.target.style.opacity = '1');
                        square.appendChild(pieceEl);
                    }
                    square.addEventListener('dragover', e => e.preventDefault());
                    square.addEventListener('drop', e => handleDrop(e, row, col));
                    square.addEventListener('click', () => handleClick(row, col));
                    boardEl.appendChild(square);
                }
            }
        }

        function handleDragStart(e, row, col) {
            // Can't move if it's not player's turn or bot is thinking
            if (state.botThinking) return e.preventDefault();
            if (state.vsBot && state.turn !== state.playerColor) return e.preventDefault();

            const piece = state.board[row][col];
            if (!piece || piece[0] !== state.turn) return e.preventDefault();
            state.selectedSquare = { row, col };
            state.validMoves = getValidMoves(row, col, piece, state.board);
            setTimeout(() => { e.target.style.opacity = '0.4'; renderBoard(); }, 0);
        }

        function handleDrop(e, row, col) {
            e.preventDefault();
            if (!state.selectedSquare) return;
            const move = state.validMoves.find(m => m.row === row && m.col === col);
            if (move) executeMove(state.selectedSquare, { row, col });
            state.selectedSquare = null; state.validMoves = [];
            renderBoard();
        }

        function handleClick(row, col) {
            if (state.botThinking) return;
            if (state.vsBot && state.turn !== state.playerColor) return;

            const piece = state.board[row][col];
            if (piece && piece[0] === state.turn) {
                state.selectedSquare = { row, col };
                state.validMoves = getValidMoves(row, col, piece, state.board);
                renderBoard();
                return;
            }
            if (state.selectedSquare) {
                const move = state.validMoves.find(m => m.row === row && m.col === col);
                if (move) executeMove(state.selectedSquare, { row, col });
                state.selectedSquare = null; state.validMoves = [];
                renderBoard();
            }
        }

        function getValidMoves(row, col, piece, board) {
            const moves = [], color = piece[0], type = piece[1];
            const isValid = (r, c) => r >= 0 && r < 8 && c >= 0 && c < 8;
            const addMove = (r, c) => {
                if (!isValid(r, c)) return false;
                const target = board[r][c];
                if (target && target[0] === color) return false;
                moves.push({ row: r, col: c });
                return !target;
            };
            if (type === 'p') {
                const dir = color === 'w' ? -1 : 1, startRow = color === 'w' ? 6 : 1;
                if (isValid(row + dir, col) && !board[row + dir][col]) {
                    moves.push({ row: row + dir, col });
                    if (row === startRow && !board[row + dir * 2][col]) moves.push({ row: row + dir * 2, col });
                }
                [[dir, -1], [dir, 1]].forEach(([dr, dc]) => {
                    const r = row + dr, c = col + dc;
                    if (isValid(r, c) && board[r][c] && board[r][c][0] !== color) moves.push({ row: r, col: c });
                });
            }
            if (type === 'n') [[-2, -1], [-2, 1], [-1, -2], [-1, 2], [1, -2], [1, 2], [2, -1], [2, 1]].forEach(([dr, dc]) => addMove(row + dr, col + dc));
            if (type === 'k') [[-1, -1], [-1, 0], [-1, 1], [0, -1], [0, 1], [1, -1], [1, 0], [1, 1]].forEach(([dr, dc]) => addMove(row + dr, col + dc));
            if (type === 'r' || type === 'q') [[-1, 0], [1, 0], [0, -1], [0, 1]].forEach(([dr, dc]) => { let r = row + dr, c = col + dc; while (addMove(r, c)) { r += dr; c += dc; } });
            if (type === 'b' || type === 'q') [[-1, -1], [-1, 1], [1, -1], [1, 1]].forEach(([dr, dc]) => { let r = row + dr, c = col + dc; while (addMove(r, c)) { r += dr; c += dc; } });
            return moves;
        }

        function executeMove(from, to) {
            if (state.gameOver) return;
            const piece = state.board[from.row][from.col];
            const captured = state.board[to.row][to.col];

            // Log History
            const notation = `${UNICODE[piece]} ${String.fromCharCode(97 + from.col)}${8 - from.row} → ${String.fromCharCode(97 + to.col)}${8 - to.row}`;
            state.history.push(notation);
            const historyItem = document.createElement('div');
            historyItem.className = 'history-item';
            historyItem.innerHTML = `<span class="move-idx">#${state.history.length}</span> <span class="move-text">${notation}</span>`;
            historyList.prepend(historyItem);

            state.board = applyMove(state.board, { from, to });
            state.lastMove = { from, to };
            state.turn = state.turn === 'w' ? 'b' : 'w';
            if (captured) {
                playSound('capture');
                state.captured[captured[0]].push(captured);
                updateCapturedDisplay();
            } else {
                playSound('move');
            }
            renderBoard();
            updateUI();

            const moves = getAllMoves(state.board, state.turn);
            if (moves.length === 0) showGameOver();
            else if (state.vsBot && state.turn !== state.playerColor) {
                thinkingEl.classList.add('show');
                setTimeout(makeBotMove, 400);
            }
        }

        function showGameOver() {
            state.gameOver = true;
            const whiteMoves = getAllMoves(state.board, 'w');
            const blackMoves = getAllMoves(state.board, 'b');
            let resultText = "";

            if (whiteMoves.length === 0) {
                state.winner = 'b';
                state.score.black++;
                resultText = "Black Wins! (Checkmate)";
                if (state.playerColor === 'b') {
                    updateStreak(true);
                    confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
                } else updateStreak(false);
            } else if (blackMoves.length === 0) {
                state.winner = 'w';
                state.score.white++;
                resultText = "White Wins! (Checkmate)";
                if (state.playerColor === 'w') {
                    updateStreak(true);
                    confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
                } else updateStreak(false);
            } else {
                resultText = "Draw!";
                updateStreak(false);
            }

            const quote = state.winner === state.playerColor ? WIN_QUOTES[Math.floor(Math.random() * WIN_QUOTES.length)] : LOSS_QUOTES[Math.floor(Math.random() * LOSS_QUOTES.length)];

            statusEl.textContent = resultText;
            document.getElementById('game-quote').textContent = quote;
            updateScoreDisplay();

            // Auto Game Reset after 5 seconds
            setTimeout(resetGame, 5000);
        }

        function updateScoreDisplay() {
            let scoreEl = document.getElementById('score-display');
            if (!scoreEl) {
                scoreEl = document.createElement('div'); scoreEl.id = 'score-display';
                scoreEl.style.cssText = 'text-align:center;margin-top:10px;font-size:1rem;color:#d4af37;font-weight:600;';
                document.querySelector('.status-card').appendChild(scoreEl);
            }
            scoreEl.innerHTML = `⚪ White: ${state.score.white} - ${state.score.black} :Black ⚫`;
        }

        function evaluateBoard(board) {
            let score = 0;
            const useAdvanced = (state.difficulty === 'hard' || state.difficulty === 'dewa');

            for (let r = 0; r < 8; r++) {
                const row = board[r];
                for (let c = 0; c < 8; c++) {
                    const piece = row[c];
                    if (piece) {
                        const color = piece[0];
                        const type = piece[1];
                        let value = PIECE_VALUES[type] || 0;

                        // Positioning bonuses
                        if (type === 'p') value += (color === 'w' ? PAWN_TABLE[r][c] : PAWN_TABLE[7 - r][c]);
                        else if (type === 'n') value += (color === 'w' ? KNIGHT_TABLE[r][c] : KNIGHT_TABLE[7 - r][c]);
                        else if (useAdvanced) {
                            if (type === 'b') value += (color === 'w' ? BISHOP_TABLE[r][c] : BISHOP_TABLE[7 - r][c]);
                            else if (type === 'r') value += (color === 'w' ? ROOK_TABLE[r][c] : ROOK_TABLE[7 - r][c]);
                            else if (type === 'q') value += (color === 'w' ? QUEEN_TABLE[r][c] : QUEEN_TABLE[7 - r][c]);
                            else if (type === 'k') value += (color === 'w' ? KING_TABLE_MID[r][c] : KING_TABLE_MID[7 - r][c]);
                        }

                        score += (color === 'b' ? value : -value);
                    }
                }
            }
            return score;
        }

        function getAllMoves(board, color) {
            const moves = [];
            for (let r = 0; r < 8; r++) for (let c = 0; c < 8; c++) {
                const piece = board[r][c];
                if (piece && piece[0] === color) getValidMoves(r, c, piece, board).forEach(m => moves.push({ from: { row: r, col: c }, to: m }));
            }
            return moves;
        }

        function cloneBoard(board) {
            const b = new Array(8);
            for (let i = 0; i < 8; i++) b[i] = board[i].slice();
            return b;
        }

        function applyMove(board, move) {
            const newBoard = cloneBoard(board);
            const piece = newBoard[move.from.row][move.from.col];
            newBoard[move.to.row][move.to.col] = piece;
            newBoard[move.from.row][move.from.col] = null;
            if (piece && piece[1] === 'p' && ((piece[0] === 'w' && move.to.row === 0) || (piece[0] === 'b' && move.to.row === 7))) {
                newBoard[move.to.row][move.to.col] = piece[0] + 'q';
            }
            return newBoard;
        }

        function minimax(board, depth, alpha, beta, isMax, startTime) {
            // Hard timeout check - scalable by difficulty
            const timeLimit = state.difficulty === 'dewa' ? 2000 : (state.difficulty === 'hard' ? 1000 : 500);
            if (Date.now() - startTime > timeLimit) return evaluateBoard(board);

            if (depth === 0) return evaluateBoard(board);
            const moves = getAllMoves(board, isMax ? 'b' : 'w');
            if (moves.length === 0) return isMax ? -10000 : 10000;

            if (isMax) {
                let maxEval = -Infinity;
                for (const move of moves) {
                    maxEval = Math.max(maxEval, minimax(applyMove(board, move), depth - 1, alpha, beta, false, startTime));
                    alpha = Math.max(alpha, maxEval); if (beta <= alpha) break;
                }
                return maxEval;
            } else {
                let minEval = Infinity;
                for (const move of moves) {
                    minEval = Math.min(minEval, minimax(applyMove(board, move), depth - 1, alpha, beta, true, startTime));
                    beta = Math.min(beta, minEval); if (beta <= alpha) break;
                }
                return minEval;
            }
        }

        function makeBotMove() {
            const botColor = state.playerColor === 'w' ? 'b' : 'w';
            const moves = getAllMoves(state.board, botColor);
            if (moves.length === 0) return;

            const isBotBlack = (botColor === 'b');
            let bestMove = null;
            let bestScore = isBotBlack ? -Infinity : Infinity;
            const startTime = Date.now();

            // DIFFICULTY SETTINGS
            let searchDepth = 2;
            if (state.difficulty === 'easy') searchDepth = 1;
            else if (state.difficulty === 'medium') searchDepth = 2;
            else if (state.difficulty === 'hard') searchDepth = 3;
            else if (state.difficulty === 'dewa') searchDepth = 4;

            // Sort moves to improve alpha-beta pruning efficiency
            moves.sort((a, b) => {
                const aPiece = state.board[a.to.row][a.to.col];
                const bPiece = state.board[b.to.row][b.to.col];
                if (aPiece && aPiece[1] === 'k') return -1;
                if (bPiece && bPiece[1] === 'k') return 1;
                // Capture moves first
                const aVal = aPiece ? PIECE_VALUES[aPiece[1]] : 0;
                const bVal = bPiece ? PIECE_VALUES[bPiece[1]] : 0;
                return bVal - aVal;
            });

            if (state.difficulty === 'easy' && Math.random() < 0.3) {
                // Easy mode sometimes picks a random move
                bestMove = moves[Math.floor(Math.random() * moves.length)];
            } else {
                for (const move of moves) {
                    const nextBoard = applyMove(state.board, move);
                    const score = minimax(nextBoard, searchDepth - 1, -Infinity, Infinity, !isBotBlack, startTime);

                    if (isBotBlack) {
                        if (score > bestScore) { bestScore = score; bestMove = move; }
                    } else {
                        if (score < bestScore) { bestScore = score; bestMove = move; }
                    }

                    // Exit if time limit reached
                    if (Date.now() - startTime > (state.difficulty === 'dewa' ? 2000 : 1000)) break;
                }
            }

            thinkingEl.classList.remove('show');
            if (bestMove) executeMove(bestMove.from, bestMove.to);
        }

        function updateUI() {
            updateStatus();
            updateCapturedDisplay();
            renderBoard();
        }

        function updateStatus() {
            const turnText = state.turn === 'w' ? "White's Turn" : "Black's Turn";
            statusEl.textContent = state.vsBot && state.turn !== state.playerColor ? "AI's Turn" : turnText;
            turnGem.className = `gem ${state.turn === 'w' ? 'white' : 'black'}`;
        }

        function updateCapturedDisplay() {
            whiteCapturedEl.textContent = state.captured.w.map(p => UNICODE[p]).join('');
            blackCapturedEl.textContent = state.captured.b.map(p => UNICODE[p]).join('');
        }

        // Optimized Restart logic to avoid any pending timeouts
        function resetGame() {
            state.board = JSON.parse(JSON.stringify(INITIAL_BOARD));
            state.turn = 'w'; state.selectedSquare = null; state.validMoves = [];
            state.captured = { w: [], b: [] }; state.lastMove = null;
            state.gameOver = false; state.winner = null;
            state.botThinking = false;
            state.history = [];
            historyList.innerHTML = '';
            thinkingEl.classList.remove('show');
            document.getElementById('game-quote').textContent = '';
            updateCapturedDisplay();
            updateUI();
            renderBoard();
            streakDisplay.textContent = `Win Streak: ${state.streak} 🔥`;
            if (state.vsBot && state.turn !== state.playerColor) {
                thinkingEl.classList.add('show');
                setTimeout(makeBotMove, 400);
            }
        }
        renderBoard();
        updateStatus();

        // Particles Generation
        function createParticles() {
            const container = document.getElementById('particles');
            const symbols = ['♔', '♕', '♚', '♛'];
            const count = 25;
            for (let i = 0; i < count; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                p.textContent = symbols[Math.floor(Math.random() * symbols.length)];

                const size = Math.random() * 15 + 15;
                const left = Math.random() * 100;
                const top = Math.random() * 100;
                const duration = Math.random() * 15 + 15;
                const delay = Math.random() * -30;

                p.style.fontSize = `${size}px`;
                p.style.left = `${left}%`;
                p.style.top = `${top + 100}%`;
                p.style.setProperty('--duration', `${duration}s`);
                p.style.setProperty('--delay', `${delay}s`);
                container.appendChild(p);
            }
        }
        createParticles();
    </script>
</body>

</html>