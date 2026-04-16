<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Parking - Admin Panel</title>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Sora:wght@600;700;800&display=swap");

    :root {
      --font-display: "Sora", "Segoe UI", Tahoma, sans-serif;
      --font-body: "Manrope", "Segoe UI", Tahoma, sans-serif;
      --space-1: 8px;
      --space-2: 16px;
      --space-3: 24px;
      --space-4: 32px;

      --bg: #edf2f7;
      --bg-accent: radial-gradient(circle at 5% 0%, #d9edff 0%, transparent 34%),
        radial-gradient(circle at 95% 12%, #f9e8ff 0%, transparent 34%),
        linear-gradient(155deg, #f7f9fc 0%, #edf2f7 100%);
      --surface: rgba(255, 255, 255, 0.72);
      --surface-strong: rgba(255, 255, 255, 0.9);
      --surface-soft: rgba(244, 248, 254, 0.95);
      --text: #102133;
      --text-muted: #607389;
      --primary: #356dff;
      --primary-strong: #214fd1;
      --success: #1fa869;
      --warning: #cc8a1d;
      --danger: #df535f;
      --border: rgba(14, 38, 58, 0.12);
      --shadow: 0 12px 30px rgba(20, 43, 67, 0.13);
      --shadow-card: 0 8px 18px rgba(17, 36, 56, 0.11);
      --radius: 20px;
      --focus: 0 0 0 3px rgba(53, 109, 255, 0.25);
    }

    body.dark {
      --bg: #081320;
      --bg-accent: radial-gradient(circle at 0% 0%, #10334b 0%, transparent 38%),
        radial-gradient(circle at 100% 8%, #341f53 0%, transparent 32%),
        linear-gradient(155deg, #08101b 0%, #0d1728 100%);
      --surface: rgba(16, 30, 48, 0.64);
      --surface-strong: rgba(14, 27, 42, 0.84);
      --surface-soft: rgba(22, 38, 58, 0.88);
      --text: #e8f2ff;
      --text-muted: #8ea5bf;
      --primary: #7da8ff;
      --primary-strong: #9bbdff;
      --success: #39d69b;
      --warning: #e6ab45;
      --danger: #ff8088;
      --border: rgba(191, 217, 245, 0.14);
      --shadow: 0 16px 36px rgba(0, 0, 0, 0.42);
      --shadow-card: 0 10px 24px rgba(0, 0, 0, 0.3);
      --focus: 0 0 0 3px rgba(125, 168, 255, 0.34);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--font-body);
      min-height: 100vh;
      color: var(--text);
      background: var(--bg);
      background-image: var(--bg-accent);
      transition: background 0.3s ease, color 0.3s ease;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: var(--space-4) var(--space-3);
    }

    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: var(--space-2);
      margin-bottom: var(--space-3);
      padding: var(--space-3);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      background: var(--surface);
      box-shadow: var(--shadow);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
    }

    .title h1 {
      font-family: var(--font-display);
      font-size: clamp(1.3rem, 2.4vw, 1.8rem);
      font-weight: 700;
      letter-spacing: 0.02em;
    }

    .title p {
      margin-top: 6px;
      font-size: 0.95rem;
      color: var(--text-muted);
    }

    .theme-toggle {
      border: 1px solid var(--border);
      border-radius: 999px;
      background: var(--surface-soft);
      color: var(--text);
      min-height: 42px;
      padding: 8px 16px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .theme-toggle:hover {
      transform: translateY(-1px);
      box-shadow: 0 8px 16px rgba(16, 30, 46, 0.16);
    }

    .theme-toggle:focus-visible {
      outline: none;
      box-shadow: var(--focus);
    }

    .layout {
      display: grid;
      grid-template-columns: 1fr;
    }

    .panel {
      width: min(100%, 980px);
      margin: 0 auto;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: var(--space-3);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      position: relative;
      overflow: hidden;
    }

    body.login-mode .panel.main {
      width: 100%;
      max-width: none;
      padding: 0;
      background: transparent;
      border: 0;
      box-shadow: none;
      backdrop-filter: none;
      -webkit-backdrop-filter: none;
      overflow: visible;
    }

    .view {
      opacity: 0;
      transform: translateY(10px);
      pointer-events: none;
      transition: opacity 0.25s ease, transform 0.25s ease;
      position: absolute;
      inset: var(--space-3);
    }

    .view.active {
      opacity: 1;
      transform: translateY(0);
      pointer-events: auto;
      position: relative;
      inset: auto;
    }

    .login-layout {
      display: grid;
      grid-template-columns: minmax(0, 440px);
      justify-content: center;
    }

    .login-copy,
    .login-card {
      border: 1px solid var(--border);
      border-radius: 18px;
      background: var(--surface-strong);
      box-shadow: var(--shadow-card);
      padding: var(--space-3);
    }

    .login-card {
      width: 100%;
      max-width: 440px;
    }

    .login-copy {
      display: none;
    }

    .login-card {
      display: grid;
      gap: var(--space-2);
      align-content: start;
    }

    .login-head {
      margin-bottom: 0;
    }

    .section-title {
      font-family: var(--font-display);
      font-size: 1rem;
      margin-bottom: 6px;
    }

    .section-subtitle {
      color: var(--text-muted);
      font-size: 0.84rem;
      line-height: 1.45;
    }

    .login-form {
      width: 100%;
      display: grid;
      gap: 12px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      color: var(--text-muted);
      font-size: 0.84rem;
      font-weight: 700;
      letter-spacing: 0.01em;
    }

    input,
    select,
    button {
      font: inherit;
    }

    input,
    select {
      width: 100%;
      min-height: 42px;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 10px 12px;
      background: var(--surface-soft);
      color: var(--text);
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    input:focus,
    select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: var(--focus);
    }

    .actions {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: var(--space-1);
    }

    .btn {
      min-height: 36px;
      border-radius: 10px;
      border: 1px solid transparent;
      padding: 0 12px;
      font-size: 0.86rem;
      line-height: 1;
      font-weight: 700;
      letter-spacing: 0.01em;
      cursor: pointer;
      transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .btn:hover {
      transform: translateY(-1px);
      filter: brightness(1.02);
    }

    .btn:focus-visible {
      outline: none;
      box-shadow: var(--focus);
    }

    .btn-primary {
      background: linear-gradient(180deg, color-mix(in srgb, var(--primary) 84%, #a5c2ff 16%) 0%, var(--primary) 100%);
      color: #fff;
      border-color: color-mix(in srgb, var(--primary) 62%, #1a3c9e 38%);
      box-shadow: 0 5px 12px color-mix(in srgb, var(--primary) 28%, transparent);
    }

    .btn-secondary {
      background: color-mix(in srgb, var(--surface-soft) 84%, #dbe9ff 16%);
      color: var(--text);
      border-color: color-mix(in srgb, var(--border) 74%, var(--primary) 26%);
    }

    .btn-danger {
      background: linear-gradient(180deg, color-mix(in srgb, var(--danger) 76%, #ffafb4 24%) 0%, color-mix(in srgb, var(--danger) 88%, #8f1f2b 12%) 100%);
      color: #fff;
      border-color: color-mix(in srgb, var(--danger) 62%, #7e1522 38%);
      box-shadow: 0 5px 12px color-mix(in srgb, var(--danger) 24%, transparent);
    }

    .status {
      margin-top: 2px;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 10px 12px;
      font-size: 0.9rem;
      background: var(--surface-soft);
      color: var(--text-muted);
      transition: all 0.2s ease;
    }

    .status.ok {
      border-color: color-mix(in srgb, var(--success) 42%, transparent);
      color: var(--success);
    }

    .status.err {
      border-color: color-mix(in srgb, var(--danger) 45%, transparent);
      color: var(--danger);
    }

    .admin-header {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: var(--space-2);
      align-items: start;
      margin-bottom: var(--space-2);
    }

    .management-tools {
      border: 1px solid var(--border);
      border-radius: 16px;
      background: linear-gradient(180deg, color-mix(in srgb, var(--surface-soft) 90%, #ffffff 10%) 0%, var(--surface-soft) 100%);
      padding: 14px;
      margin-bottom: var(--space-2);
      display: grid;
      gap: 12px;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.35);
    }

    .management-head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: var(--space-2);
      padding-bottom: 12px;
      border-bottom: 1px solid color-mix(in srgb, var(--border) 70%, transparent);
    }

    .management-kicker {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 6px;
      border: 1px solid color-mix(in srgb, var(--primary) 32%, transparent);
      border-radius: 999px;
      padding: 3px 10px;
      font-size: 0.72rem;
      font-weight: 800;
      color: var(--primary);
      letter-spacing: 0.06em;
      text-transform: uppercase;
      background: color-mix(in srgb, var(--primary) 12%, transparent);
    }

    .management-head h3 {
      font-family: var(--font-display);
      font-size: 1rem;
      letter-spacing: 0.01em;
      margin-bottom: 3px;
    }

    .management-head p {
      color: var(--text-muted);
      font-size: 0.8rem;
      max-width: 460px;
      line-height: 1.35;
    }

    .management-count {
      border: 1px solid var(--border);
      border-radius: 10px;
      background: var(--surface-strong);
      padding: 8px 10px;
      min-width: 128px;
      text-align: center;
    }

    .management-count-value {
      display: block;
      font-family: var(--font-display);
      font-size: 1rem;
      font-weight: 700;
      line-height: 1.1;
    }

    .management-count-label {
      font-size: 0.74rem;
      color: var(--text-muted);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .tools-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: var(--space-2);
    }

    .management-body.collapsed {
      display: none;
    }

    .tool-card {
      border: 1px solid var(--border);
      border-radius: 14px;
      background: var(--surface-strong);
      padding: 12px;
      display: grid;
      gap: 8px;
      box-shadow: var(--shadow-card);
    }

    .tool-card-slot {
      align-content: start;
    }

    .tool-head {
      display: flex;
      align-items: start;
      justify-content: space-between;
      gap: 10px;
    }

    .tool-body {
      display: grid;
      gap: var(--space-1);
    }

    .tool-body.collapsed {
      display: none;
    }

    .tool-collapse-btn {
      min-height: 28px;
      min-width: 28px;
      border-radius: 8px;
      border: 1px solid var(--border);
      background: var(--surface-soft);
      color: var(--text);
      font-size: 0.8rem;
      font-weight: 700;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .tool-collapse-btn:hover {
      transform: translateY(-1px);
    }

    .tool-card-slot {
      border-left: 4px solid color-mix(in srgb, var(--primary) 58%, transparent);
    }

    .tool-card-zone {
      border-left: 4px solid color-mix(in srgb, var(--warning) 58%, transparent);
    }

    .tool-title {
      font-size: 0.7rem;
      color: var(--text-muted);
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .tool-subtitle {
      font-size: 0.76rem;
      color: var(--text-muted);
      margin-top: -2px;
      line-height: 1.25;
    }

    .tool-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: var(--space-1);
    }

    .tool-row-3 {
      display: grid;
      grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.9fr) auto;
      gap: 8px;
      align-items: center;
    }

    .management-tools input,
    .management-tools select {
      min-height: 36px;
      font-size: 0.8rem;
      background: color-mix(in srgb, var(--surface-soft) 88%, #ffffff 12%);
    }

    .management-tools .btn {
      min-height: 36px;
      font-size: 0.76rem;
      padding-inline: 12px;
      white-space: nowrap;
    }

    .zone-summary {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 6px;
      margin-top: 2px;
    }

    .zone-summary-item {
      border: 1px solid var(--border);
      border-radius: 10px;
      background: var(--surface-soft);
      padding: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 6px;
    }

    .zone-summary-name {
      font-weight: 700;
      font-size: 0.84rem;
    }

    .zone-summary-count {
      font-size: 0.76rem;
      color: var(--text-muted);
      font-weight: 700;
    }

    .toolbar {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: var(--space-1);
      width: min(100%, 420px);
    }

    .toolbar .btn {
      width: 100%;
      min-width: 0;
    }

    .quick-stats {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: var(--space-1);
      margin-bottom: var(--space-2);
    }

    .slot-controls {
      border: 1px solid var(--border);
      border-radius: 12px;
      background: var(--surface-soft);
      padding: 10px;
      margin-bottom: var(--space-2);
      display: grid;
      grid-template-columns: minmax(180px, 1fr) minmax(150px, 180px);
      gap: var(--space-1);
    }

    .slot-empty {
      border: 1px dashed var(--border);
      border-radius: 12px;
      padding: 14px;
      color: var(--text-muted);
      font-size: 0.88rem;
      text-align: center;
      background: var(--surface-soft);
    }

    .stat-chip {
      border: 1px solid var(--border);
      border-radius: 12px;
      background: var(--surface-soft);
      padding: 10px;
    }

    .stat-chip-label {
      font-size: 0.76rem;
      color: var(--text-muted);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .stat-chip-value {
      margin-top: 4px;
      font-family: var(--font-display);
      font-size: 1.15rem;
      font-weight: 700;
    }

    .stat-chip-free .stat-chip-value { color: var(--success); }
    .stat-chip-occ .stat-chip-value { color: var(--danger); }
    .stat-chip-main .stat-chip-value { color: var(--warning); }

    .slot-list {
      display: grid;
      gap: var(--space-2);
      max-height: 58vh;
      overflow: auto;
      padding-right: var(--space-1);
    }

    .zone-group {
      border: 1px solid var(--border);
      border-radius: 14px;
      background: color-mix(in srgb, var(--surface-soft) 82%, transparent);
      padding: 10px;
      display: grid;
      gap: 10px;
    }

    .zone-group-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: var(--space-1);
      flex-wrap: wrap;
      padding-bottom: 8px;
      border-bottom: 1px dashed var(--border);
    }

    .zone-head-left {
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .zone-toggle {
      min-height: 28px;
      min-width: 28px;
      border-radius: 8px;
      border: 1px solid var(--border);
      background: var(--surface-soft);
      color: var(--text);
      font-size: 0.8rem;
      font-weight: 700;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .zone-toggle:hover {
      transform: translateY(-1px);
      box-shadow: var(--shadow-card);
    }

    .zone-group-body {
      display: grid;
      gap: 10px;
    }

    .zone-group.collapsed .zone-group-body {
      display: none;
    }

    .zone-group-title {
      font-family: var(--font-display);
      font-size: 0.88rem;
      font-weight: 700;
      letter-spacing: 0.02em;
    }

    .zone-group-meta {
      color: var(--text-muted);
      font-size: 0.74rem;
      font-weight: 700;
    }

    .slot-row {
      border: 1px solid var(--border);
      border-radius: 14px;
      background: var(--surface-strong);
      box-shadow: var(--shadow-card);
      padding: var(--space-2);
      display: grid;
      grid-template-columns: minmax(72px, 104px) minmax(110px, 0.95fr) minmax(140px, 180px) minmax(92px, 106px);
      gap: 10px;
      align-items: center;
      transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .row-actions {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 6px;
    }

    .btn-mini {
      min-height: 28px;
      font-size: 0.7rem;
      padding: 0 7px;
    }

    .modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(5, 12, 22, 0.58);
      display: none;
      align-items: center;
      justify-content: center;
      padding: var(--space-2);
      z-index: 50;
      backdrop-filter: blur(3px);
      -webkit-backdrop-filter: blur(3px);
    }

    .modal-backdrop.active {
      display: flex;
    }

    .modal-card {
      width: min(100%, 440px);
      border: 1px solid var(--border);
      border-radius: 14px;
      background: var(--surface-strong);
      box-shadow: var(--shadow);
      padding: var(--space-2);
      display: grid;
      gap: 12px;
    }

    .modal-title {
      font-family: var(--font-display);
      font-size: 1.02rem;
      line-height: 1.2;
    }

    .modal-message {
      color: var(--text-muted);
      font-size: 0.9rem;
      line-height: 1.45;
    }

    .modal-input-wrap {
      display: grid;
      gap: 6px;
    }

    .modal-actions {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: var(--space-1);
    }

    .modal-hidden {
      display: none;
    }

    .slot-row:hover {
      transform: translateY(-1px);
      border-color: color-mix(in srgb, var(--primary) 44%, var(--border) 56%);
    }

    .slot-id {
      font-family: var(--font-display);
      font-weight: 700;
      letter-spacing: 0.02em;
      font-size: 1rem;
    }

    .state-label {
      font-weight: 700;
      font-size: 0.9rem;
    }

    .state-0 { color: var(--success); }
    .state-1 { color: var(--danger); }
    .state-2 { color: var(--warning); }

    .footer-status {
      margin-top: var(--space-2);
    }

    .skeleton {
      height: 58px;
      border-radius: 14px;
      border: 1px solid var(--border);
      background: linear-gradient(90deg, var(--surface-soft) 25%, color-mix(in srgb, var(--surface-soft) 72%, #fff 28%) 50%, var(--surface-soft) 75%);
      background-size: 220% 100%;
      animation: shimmer 1.2s infinite linear;
    }

    @keyframes shimmer {
      from { background-position: 100% 0; }
      to { background-position: -100% 0; }
    }

    @media (max-width: 960px) {
      .quick-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

    }

    @media (max-width: 1120px) {
      .slot-row {
        grid-template-columns: 1fr 1fr;
      }

      .slot-row > select {
        grid-column: 1;
      }

      .row-actions {
        grid-column: 2;
      }

      .tool-row-3 {
        grid-template-columns: 1fr 1fr;
      }

      .tool-row-3 > button {
        grid-column: 1 / -1;
      }

      .tool-card-slot .tool-row-3 {
        grid-template-columns: 1fr 1fr;
      }

      .management-head p {
        max-width: 100%;
      }
    }

    @media (max-width: 760px) {
      .container {
        padding: var(--space-2);
      }

      .topbar,
      .panel {
        padding: var(--space-2);
      }

      .topbar {
        flex-direction: column;
        align-items: stretch;
      }

      .login-card {
        padding: var(--space-2);
      }

      .theme-toggle {
        width: 100%;
      }

      .actions {
        grid-template-columns: 1fr;
      }

      .admin-header {
        grid-template-columns: 1fr;
      }

      .tools-grid,
      .tool-row,
      .tool-row-3 {
        grid-template-columns: 1fr;
      }

      .management-tools {
        padding: 12px;
      }

      .tool-card {
        padding: 10px;
      }

      .tool-subtitle {
        line-height: 1.3;
      }

      .tool-row-3 > button {
        grid-column: auto;
      }

      .slot-controls {
        grid-template-columns: 1fr;
      }

      .management-head {
        flex-direction: column;
      }

      .management-count {
        width: 100%;
      }

      .zone-summary {
        grid-template-columns: 1fr;
      }

      .toolbar {
        width: 100%;
        grid-template-columns: 1fr;
      }

      .slot-row {
        grid-template-columns: 1fr;
        gap: var(--space-1);
      }

      .row-actions {
        grid-template-columns: 1fr 1fr;
        grid-column: auto;
      }

      .slot-row > select,
      .row-actions {
        width: 100%;
      }

      .management-tools .btn {
        min-height: 34px;
      }

      .management-tools input,
      .management-tools select {
        min-height: 34px;
      }

      .view {
        inset: var(--space-2);
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <header class="topbar">
      <div class="title">
        <h1>Admin Panel</h1>
        <p>Operate slot states with live API sync and controlled updates.</p>
      </div>
      <button id="themeToggle" class="theme-toggle" type="button">Toggle Theme</button>
    </header>

    <main class="layout">
      <section class="panel main">
        <div id="loginView" class="view active">
          <div class="login-layout">
            <section class="login-card">
              <div class="login-head">
                <h2 class="section-title">Sign In</h2>
                <p class="section-subtitle">Use the demo account or enter your admin credentials.</p>
              </div>

              <form id="loginForm" class="login-form" autocomplete="off">
                <div>
                  <label for="username">Username</label>
                  <input id="username" name="username" type="text" required>
                </div>

                <div>
                  <label for="password">Password</label>
                  <input id="password" name="password" type="password" required>
                </div>

                <div class="actions">
                  <button type="submit" class="btn btn-primary">Login</button>
                  <button id="demoFillBtn" type="button" class="btn btn-secondary">Use demo</button>
                </div>
              </form>

              <div id="loginStatus" class="status">Enter credentials to continue.</div>
            </section>
          </div>
        </div>

        <div id="adminView" class="view">
          <header class="admin-header">
            <div>
              <h2 class="section-title">Manage Parking Slots</h2>
              <p class="section-subtitle">Review current slot state, adjust values, then push updates.</p>
            </div>
            <div class="toolbar">
              <button id="reloadBtn" type="button" class="btn btn-secondary">Reload</button>
              <button id="saveBtn" type="button" class="btn btn-primary">Save Changes</button>
              <button id="logoutBtn" type="button" class="btn btn-danger">Logout</button>
            </div>
          </header>

          <section class="management-tools" aria-label="Zone and slot management tools">
            <div class="management-head">
              <div>
                <span class="management-kicker">Operations</span>
                <h3>Management Sections</h3>
                <p>Organized controls for slots and zones with quick admin actions.</p>
              </div>
              <div class="management-count">
                <span id="managementZoneCount" class="management-count-value">0</span>
                <span class="management-count-label">Active Zones</span>
              </div>
            </div>
            <div id="managementBody" class="management-body">
            <div class="tools-grid">
              <article class="tool-card tool-card-slot">
                <div class="tool-head">
                  <div>
                    <p class="tool-title">Parking Slot Management</p>
                    <p class="tool-subtitle">Create new slots and set their initial occupancy state.</p>
                  </div>
                  <button id="slotToolToggle" class="tool-collapse-btn" type="button" aria-label="Toggle slot management">-</button>
                </div>
                <div id="slotToolBody" class="tool-body">
                  <div class="tool-row-3">
                    <input id="newSlotId" type="text" placeholder="Slot ID (e.g. C1)">
                    <select id="newSlotState">
                      <option value="0">0 - AVAILABLE</option>
                      <option value="1">1 - OCCUPIED</option>
                      <option value="2">2 - MAINTENANCE</option>
                    </select>
                    <button id="addSlotBtn" type="button" class="btn btn-secondary">Add Slot</button>
                  </div>
                </div>
              </article>

              <article class="tool-card tool-card-zone">
                <div class="tool-head">
                  <div>
                    <p class="tool-title">Zone Management</p>
                    <p class="tool-subtitle">Create, rename, or remove complete zones and all their slots.</p>
                  </div>
                  <button id="zoneToolToggle" class="tool-collapse-btn" type="button" aria-label="Toggle zone management">+</button>
                </div>
                <div id="zoneToolBody" class="tool-body collapsed">
                  <div class="tool-row-3">
                    <input id="newZoneName" type="text" placeholder="Zone (e.g. D)">
                    <input id="newZoneCount" type="number" min="1" max="200" placeholder="Slot Count">
                    <button id="addZoneBtn" type="button" class="btn btn-secondary">Add Zone</button>
                  </div>
                  <div class="tool-row">
                    <select id="zoneSelect"></select>
                    <input id="renameZoneTo" type="text" placeholder="Rename to (e.g. E)">
                  </div>
                  <div class="tool-row">
                    <button id="renameZoneBtn" type="button" class="btn btn-secondary">Rename Zone</button>
                    <button id="deleteZoneBtn" type="button" class="btn btn-danger">Delete Zone</button>
                  </div>
                  <div id="zoneSummary" class="zone-summary"></div>
                </div>
              </article>
            </div>
            </div>
          </section>

          <section class="quick-stats" aria-label="Slot summary">
            <article class="stat-chip">
              <p class="stat-chip-label">Total</p>
              <p class="stat-chip-value" id="chipTotal">0</p>
            </article>
            <article class="stat-chip stat-chip-free">
              <p class="stat-chip-label">Available</p>
              <p class="stat-chip-value" id="chipFree">0</p>
            </article>
            <article class="stat-chip stat-chip-occ">
              <p class="stat-chip-label">Occupied</p>
              <p class="stat-chip-value" id="chipOccupied">0</p>
            </article>
            <article class="stat-chip stat-chip-main">
              <p class="stat-chip-label">Maintenance</p>
              <p class="stat-chip-value" id="chipMaintenance">0</p>
            </article>
          </section>

          <section class="slot-controls" aria-label="Slot search and sort">
            <input id="slotSearch" type="text" placeholder="Search slots (ID, zone, or state text)">
            <select id="slotSort">
              <option value="id-asc">Sort: Slot ID (A-Z)</option>
              <option value="id-desc">Sort: Slot ID (Z-A)</option>
              <option value="state-asc">Sort: State (Available → Maintenance)</option>
              <option value="state-desc">Sort: State (Maintenance → Available)</option>
            </select>
          </section>

          <div id="slotList" class="slot-list"></div>
          <div id="panelStatus" class="status footer-status">Ready.</div>
        </div>
      </section>
    </main>
  </div>

  <div id="actionModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
      <h3 id="modalTitle" class="modal-title">Confirm Action</h3>
      <p id="modalMessage" class="modal-message"></p>
      <div id="modalInputWrap" class="modal-input-wrap modal-hidden">
        <label for="modalInput">Value</label>
        <input id="modalInput" type="text" autocomplete="off">
      </div>
      <div class="modal-actions">
        <button id="modalCancelBtn" type="button" class="btn btn-secondary">Cancel</button>
        <button id="modalConfirmBtn" type="button" class="btn btn-primary">Confirm</button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    (function () {
      const API_URL = "../api.php";

      const loginView = document.getElementById("loginView");
      const adminView = document.getElementById("adminView");
      const loginForm = document.getElementById("loginForm");
      const demoFillBtn = document.getElementById("demoFillBtn");
      const loginStatus = document.getElementById("loginStatus");
      const reloadBtn = document.getElementById("reloadBtn");
      const saveBtn = document.getElementById("saveBtn");
      const logoutBtn = document.getElementById("logoutBtn");
      const slotList = document.getElementById("slotList");
      const panelStatus = document.getElementById("panelStatus");
      const themeToggle = document.getElementById("themeToggle");
      const chipTotal = document.getElementById("chipTotal");
      const chipFree = document.getElementById("chipFree");
      const chipOccupied = document.getElementById("chipOccupied");
      const chipMaintenance = document.getElementById("chipMaintenance");
      const newSlotId = document.getElementById("newSlotId");
      const newSlotState = document.getElementById("newSlotState");
      const addSlotBtn = document.getElementById("addSlotBtn");
      const newZoneName = document.getElementById("newZoneName");
      const newZoneCount = document.getElementById("newZoneCount");
      const addZoneBtn = document.getElementById("addZoneBtn");
      const zoneSelect = document.getElementById("zoneSelect");
      const renameZoneTo = document.getElementById("renameZoneTo");
      const renameZoneBtn = document.getElementById("renameZoneBtn");
      const deleteZoneBtn = document.getElementById("deleteZoneBtn");
      const zoneSummary = document.getElementById("zoneSummary");
      const managementZoneCount = document.getElementById("managementZoneCount");
      const slotToolToggle = document.getElementById("slotToolToggle");
      const zoneToolToggle = document.getElementById("zoneToolToggle");
      const slotToolBody = document.getElementById("slotToolBody");
      const zoneToolBody = document.getElementById("zoneToolBody");
      const slotSearch = document.getElementById("slotSearch");
      const slotSort = document.getElementById("slotSort");
      const actionModal = document.getElementById("actionModal");
      const modalTitle = document.getElementById("modalTitle");
      const modalMessage = document.getElementById("modalMessage");
      const modalInputWrap = document.getElementById("modalInputWrap");
      const modalInput = document.getElementById("modalInput");
      const modalCancelBtn = document.getElementById("modalCancelBtn");
      const modalConfirmBtn = document.getElementById("modalConfirmBtn");

      let slots = {};
      const collapsedZones = new Set();
      let modalConfirmHandler = null;

      function setTheme(isDark) {
        document.body.classList.toggle("dark", isDark);
        localStorage.setItem("parking-theme", isDark ? "dark" : "light");
        themeToggle.textContent = isDark ? "Switch to Light" : "Switch to Dark";
      }

      function initTheme() {
        const savedTheme = localStorage.getItem("parking-theme");
        if (savedTheme) {
          setTheme(savedTheme === "dark");
          return;
        }
        const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        setTheme(prefersDark);
      }

      zoneToolBody.classList.add("collapsed");
      zoneToolToggle.textContent = "+";

      function setStatus(target, message, type) {
        target.textContent = message;
        target.classList.remove("ok", "err");
        if (type === "ok") {
          target.classList.add("ok");
        } else if (type === "err") {
          target.classList.add("err");
        }
      }

      function showAlert(message, icon) {
        if (window.Swal && typeof window.Swal.fire === "function") {
          window.Swal.fire({
            icon: icon || "error",
            title: icon === "success" ? "Success" : "Oops",
            text: message,
            confirmButtonText: "OK",
            confirmButtonColor: "#356dff",
            background: document.body.classList.contains("dark") ? "#0f1a29" : "#ffffff",
            color: getComputedStyle(document.body).getPropertyValue("--text").trim() || "#102133"
          });
          return;
        }
        window.alert(message);
      }

      function showSkeletons() {
        slotList.innerHTML = "";
        for (let i = 0; i < 4; i += 1) {
          const sk = document.createElement("div");
          sk.className = "skeleton";
          slotList.appendChild(sk);
        }
      }

      function stateToLabel(value) {
        if (value === 0) return "Available";
        if (value === 1) return "Occupied";
        return "Maintenance";
      }

      function stateClass(value) {
        if (value === 0) return "state-0";
        if (value === 1) return "state-1";
        return "state-2";
      }

      function extractZones() {
        const zones = new Set();
        Object.keys(slots).forEach(function (slotId) {
          const match = slotId.match(/^[A-Za-z]+/);
          if (match) {
            zones.add(match[0].toUpperCase());
          }
        });
        return Array.from(zones).sort();
      }

      function getGroupedSlots(slotIds) {
        const grouped = {};
        (slotIds || Object.keys(slots)).forEach(function (slotId) {
          const zone = (slotId.match(/^[A-Za-z]+/) || ["OTHER"])[0].toUpperCase();
          if (!grouped[zone]) {
            grouped[zone] = [];
          }
          grouped[zone].push(slotId);
        });
        return grouped;
      }

      function getFilteredSortedSlotIds() {
        const query = (slotSearch.value || "").trim().toLowerCase();
        const sortMode = slotSort.value || "id-asc";
        const ids = Object.keys(slots).filter(function (slotId) {
          if (!query) {
            return true;
          }
          const zone = ((slotId.match(/^[A-Za-z]+/) || [""])[0] || "").toLowerCase();
          const stateText = stateToLabel(Number(slots[slotId])).toLowerCase();
          return slotId.toLowerCase().includes(query) || zone.includes(query) || stateText.includes(query);
        });

        ids.sort(function (a, b) {
          if (sortMode === "id-desc") {
            return b.localeCompare(a, undefined, { numeric: true, sensitivity: "base" });
          }
          if (sortMode === "state-asc") {
            const byState = Number(slots[a]) - Number(slots[b]);
            if (byState !== 0) {
              return byState;
            }
            return a.localeCompare(b, undefined, { numeric: true, sensitivity: "base" });
          }
          if (sortMode === "state-desc") {
            const byState = Number(slots[b]) - Number(slots[a]);
            if (byState !== 0) {
              return byState;
            }
            return a.localeCompare(b, undefined, { numeric: true, sensitivity: "base" });
          }
          return a.localeCompare(b, undefined, { numeric: true, sensitivity: "base" });
        });

        return ids;
      }

      function renderZoneSelector() {
        const zones = extractZones();
        managementZoneCount.textContent = String(zones.length);
        zoneSelect.innerHTML = "";
        if (zones.length === 0) {
          const option = document.createElement("option");
          option.value = "";
          option.textContent = "No zones";
          zoneSelect.appendChild(option);
          return;
        }

        zones.forEach(function (zone) {
          const option = document.createElement("option");
          option.value = zone;
          option.textContent = "Zone " + zone;
          zoneSelect.appendChild(option);
        });
      }

      function renderZoneSummary() {
        const grouped = getGroupedSlots();
        const zoneNames = Object.keys(grouped).sort();
        zoneSummary.innerHTML = "";

        if (zoneNames.length === 0) {
          const item = document.createElement("div");
          item.className = "zone-summary-item";
          item.innerHTML = "<span class=\"zone-summary-name\">No zones</span><span class=\"zone-summary-count\">0 slots</span>";
          zoneSummary.appendChild(item);
          return;
        }

        zoneNames.forEach(function (zone) {
          const count = grouped[zone].length;
          const item = document.createElement("div");
          item.className = "zone-summary-item";
          item.innerHTML =
            "<span class=\"zone-summary-name\">Zone " + zone + "</span>" +
            "<span class=\"zone-summary-count\">" + count + " slots</span>";
          zoneSummary.appendChild(item);
        });
      }

      function renderSummary() {
        const values = Object.values(slots).map(Number);
        const total = values.length;
        const free = values.filter(function (state) { return state === 0; }).length;
        const occupied = values.filter(function (state) { return state === 1; }).length;
        const maintenance = values.filter(function (state) { return state === 2; }).length;

        chipTotal.textContent = String(total);
        chipFree.textContent = String(free);
        chipOccupied.textContent = String(occupied);
        chipMaintenance.textContent = String(maintenance);
      }

      function openModal(config) {
        modalTitle.textContent = config.title || "Confirm";
        modalMessage.textContent = config.message || "";
        modalConfirmBtn.textContent = config.confirmText || "Confirm";
        modalConfirmBtn.classList.remove("btn-primary", "btn-danger");
        modalConfirmBtn.classList.add(config.danger ? "btn-danger" : "btn-primary");

        if (config.input) {
          modalInputWrap.classList.remove("modal-hidden");
          modalInput.value = config.inputValue || "";
          modalInput.placeholder = config.inputPlaceholder || "";
          setTimeout(function () { modalInput.focus(); }, 0);
        } else {
          modalInputWrap.classList.add("modal-hidden");
          modalInput.value = "";
        }

        modalConfirmHandler = config.onConfirm || null;
        actionModal.classList.add("active");
        actionModal.setAttribute("aria-hidden", "false");
      }

      function closeModal() {
        actionModal.classList.remove("active");
        actionModal.setAttribute("aria-hidden", "true");
        modalConfirmHandler = null;
      }

      function createSlotRow(slotId, value) {
        const row = document.createElement("div");
        row.className = "slot-row";

        const idEl = document.createElement("div");
        idEl.className = "slot-id";
        idEl.textContent = slotId;

        const labelEl = document.createElement("div");
        labelEl.className = "state-label " + stateClass(value);
        labelEl.textContent = stateToLabel(value);

        const select = document.createElement("select");
        select.dataset.slotId = slotId;
        select.innerHTML =
          '<option value="0">0 - AVAILABLE</option>' +
          '<option value="1">1 - OCCUPIED</option>' +
          '<option value="2">2 - MAINTENANCE</option>';
        select.value = String(value);

        select.addEventListener("change", function () {
          const next = Number(select.value);
          slots[slotId] = next;
          labelEl.textContent = stateToLabel(next);
          labelEl.className = "state-label " + stateClass(next);
          renderSummary();
        });

        const actionsEl = document.createElement("div");
        actionsEl.className = "row-actions";

        const renameBtn = document.createElement("button");
        renameBtn.type = "button";
        renameBtn.className = "btn btn-secondary btn-mini";
        renameBtn.textContent = "Rename";
        renameBtn.addEventListener("click", function () {
          openModal({
            title: "Rename Slot",
            message: "Enter a new ID for " + slotId + " (example: C2).",
            confirmText: "Rename",
            input: true,
            inputValue: slotId,
            onConfirm: function () {
              const nextId = (modalInput.value || "").trim().toUpperCase();
              if (!nextId || nextId === slotId) {
                closeModal();
                return;
              }
              closeModal();
              renameSlot(slotId, nextId);
            }
          });
        });

        const deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.className = "btn btn-danger btn-mini";
        deleteBtn.textContent = "Delete";
        deleteBtn.addEventListener("click", function () {
          openModal({
            title: "Delete Slot",
            message: "Delete slot " + slotId + "? This action cannot be undone.",
            confirmText: "Delete",
            danger: true,
            onConfirm: function () {
              closeModal();
              deleteSlot(slotId);
            }
          });
        });

        actionsEl.appendChild(renameBtn);
        actionsEl.appendChild(deleteBtn);

        row.appendChild(idEl);
        row.appendChild(labelEl);
        row.appendChild(select);
        row.appendChild(actionsEl);

        return row;
      }

      function renderSlots() {
        slotList.innerHTML = "";
        const visibleIds = getFilteredSortedSlotIds();
        const grouped = getGroupedSlots(visibleIds);
        const zones = Object.keys(grouped).sort();

        renderSummary();
        renderZoneSelector();
        renderZoneSummary();

        if (zones.length === 0) {
          const emptyNote = document.createElement("div");
          emptyNote.className = "slot-empty";
          emptyNote.textContent = Object.keys(slots).length === 0 ? "No slots available." : "No slots match current search/filter.";
          slotList.appendChild(emptyNote);
          return;
        }

        zones.forEach(function (zone) {
          const zoneWrap = document.createElement("section");
          zoneWrap.className = "zone-group";
          if (collapsedZones.has(zone)) {
            zoneWrap.classList.add("collapsed");
          }

          const zoneSlots = grouped[zone];
          const free = zoneSlots.filter(function (slotId) { return Number(slots[slotId]) === 0; }).length;
          const occupied = zoneSlots.filter(function (slotId) { return Number(slots[slotId]) === 1; }).length;
          const maintenance = zoneSlots.filter(function (slotId) { return Number(slots[slotId]) === 2; }).length;

          const head = document.createElement("header");
          head.className = "zone-group-head";
          const toggleIcon = collapsedZones.has(zone) ? "+" : "-";
          head.innerHTML =
            "<div class=\"zone-head-left\">" +
            "<button type=\"button\" class=\"zone-toggle\" aria-label=\"Toggle Zone " + zone + "\">" + toggleIcon + "</button>" +
            "<div class=\"zone-group-title\">Zone " + zone + "</div>" +
            "</div>" +
            "<div class=\"zone-group-meta\">Total: " + zoneSlots.length +
            " | Free: " + free +
            " | Occupied: " + occupied +
            " | Maintenance: " + maintenance + "</div>";
          zoneWrap.appendChild(head);

          const toggleBtn = head.querySelector(".zone-toggle");
          toggleBtn.addEventListener("click", function () {
            if (collapsedZones.has(zone)) {
              collapsedZones.delete(zone);
            } else {
              collapsedZones.add(zone);
            }
            renderSlots();
          });

          const zoneBody = document.createElement("div");
          zoneBody.className = "zone-group-body";

          zoneSlots.forEach(function (slotId) {
            zoneBody.appendChild(createSlotRow(slotId, Number(slots[slotId])));
          });

          zoneWrap.appendChild(zoneBody);

          slotList.appendChild(zoneWrap);
        });
      }

      async function fetchSlots() {
        showSkeletons();
        try {
          const response = await fetch(API_URL, { cache: "no-store" });
          if (!response.ok) {
            throw new Error("Request failed with status " + response.status);
          }
          const data = await response.json();
          slots = data && data.slots ? { ...data.slots } : {};
          renderSlots();
          setStatus(panelStatus, "Slot data loaded.", "ok");
        } catch (error) {
          slotList.innerHTML = "";
          chipTotal.textContent = "0";
          chipFree.textContent = "0";
          chipOccupied.textContent = "0";
          chipMaintenance.textContent = "0";
          renderZoneSelector();
          renderZoneSummary();
          showAlert("Failed to load slots: " + error.message, "error");
          setStatus(panelStatus, "Failed to load slots.", "err");
        }
      }

      async function postAction(payload) {
        const body = new URLSearchParams();
        Object.keys(payload).forEach(function (key) {
          body.set(key, String(payload[key]));
        });

        const response = await fetch(API_URL, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: body.toString()
        });

        const result = await response.json();
        if (!response.ok || (result && result.status === "error")) {
          throw new Error((result && result.message) || "API request failed");
        }

        return result;
      }

      async function postSlotState(slotId, state) {
        await postAction({ action: "update", slot: slotId, state: state });
      }

      async function renameSlot(oldSlotId, newSlotId) {
        try {
          const result = await postAction({ action: "renameSlot", oldSlot: oldSlotId, newSlot: newSlotId.toUpperCase() });
          slots = result && result.slots ? { ...result.slots } : {};
          renderSlots();
          setStatus(panelStatus, result.message || "Slot renamed.", "ok");
        } catch (error) {
          showAlert("Rename error: " + error.message, "error");
          setStatus(panelStatus, "Rename error.", "err");
        }
      }

      async function deleteSlot(slotId) {
        try {
          const result = await postAction({ action: "deleteSlot", slot: slotId });
          slots = result && result.slots ? { ...result.slots } : {};
          renderSlots();
          setStatus(panelStatus, result.message || "Slot deleted.", "ok");
        } catch (error) {
          showAlert("Delete error: " + error.message, "error");
          setStatus(panelStatus, "Delete error.", "err");
        }
      }

      async function addSlot() {
        const slotId = (newSlotId.value || "").trim().toUpperCase();
        const state = Number(newSlotState.value);
        if (!slotId) {
          showAlert("Enter a slot ID (example: C1).", "error");
          return;
        }

        try {
          const result = await postAction({ action: "addSlot", slot: slotId, state: state });
          slots = result && result.slots ? { ...result.slots } : {};
          renderSlots();
          newSlotId.value = "";
          setStatus(panelStatus, result.message || "Slot added.", "ok");
        } catch (error) {
          showAlert("Add slot error: " + error.message, "error");
          setStatus(panelStatus, "Add slot error.", "err");
        }
      }

      async function addZone() {
        const zone = (newZoneName.value || "").trim().toUpperCase();
        const count = Number(newZoneCount.value);
        if (!zone || !count) {
          showAlert("Enter zone name and slot count.", "error");
          return;
        }

        try {
          const result = await postAction({ action: "addZone", zone: zone, count: count, state: 0 });
          slots = result && result.slots ? { ...result.slots } : {};
          renderSlots();
          newZoneName.value = "";
          newZoneCount.value = "";
          setStatus(panelStatus, result.message || "Zone added.", "ok");
        } catch (error) {
          showAlert("Add zone error: " + error.message, "error");
          setStatus(panelStatus, "Add zone error.", "err");
        }
      }

      async function renameZone() {
        const oldZone = zoneSelect.value;
        const newZone = (renameZoneTo.value || "").trim().toUpperCase();
        if (!oldZone || !newZone) {
          showAlert("Select zone and enter new zone name.", "error");
          return;
        }

        try {
          const result = await postAction({ action: "renameZone", oldZone: oldZone, newZone: newZone });
          slots = result && result.slots ? { ...result.slots } : {};
          renderSlots();
          renameZoneTo.value = "";
          setStatus(panelStatus, result.message || "Zone renamed.", "ok");
        } catch (error) {
          showAlert("Rename zone error: " + error.message, "error");
          setStatus(panelStatus, "Rename zone error.", "err");
        }
      }

      async function deleteZone() {
        const zone = zoneSelect.value;
        if (!zone) {
          showAlert("Select a zone to delete.", "error");
          return;
        }
        openModal({
          title: "Delete Zone",
          message: "Delete Zone " + zone + " and all its slots? This cannot be undone.",
          confirmText: "Delete Zone",
          danger: true,
          onConfirm: async function () {
            closeModal();
            try {
              const result = await postAction({ action: "deleteZone", zone: zone });
              slots = result && result.slots ? { ...result.slots } : {};
              collapsedZones.delete(zone);
              renderSlots();
              setStatus(panelStatus, result.message || "Zone deleted.", "ok");
            } catch (error) {
              showAlert("Delete zone error: " + error.message, "error");
              setStatus(panelStatus, "Delete zone error.", "err");
            }
          }
        });
      }

      async function saveAllSlots() {
        const entries = Object.entries(slots);
        if (entries.length === 0) {
          setStatus(panelStatus, "No slot changes to save.", "err");
          return;
        }

        saveBtn.disabled = true;
        saveBtn.textContent = "Saving...";
        setStatus(panelStatus, "Saving slot changes...", "");

        try {
          for (const [slotId, state] of entries) {
            await postSlotState(slotId, state);
          }
          setStatus(panelStatus, "All slot changes saved.", "ok");
        } catch (error) {
          setStatus(panelStatus, "Save error: " + error.message, "err");
        } finally {
          saveBtn.disabled = false;
          saveBtn.textContent = "Save Changes";
        }
      }

      function showAdminPanel() {
        document.body.classList.remove("login-mode");
        loginView.classList.remove("active");
        adminView.classList.add("active");
      }

      function showLogin() {
        document.body.classList.add("login-mode");
        adminView.classList.remove("active");
        loginView.classList.add("active");
      }

      loginForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value.trim();

        if (username === "demo" && password === "demo") {
          setStatus(loginStatus, "Login successful.", "ok");
          showAdminPanel();
          fetchSlots();
        } else {
          showAlert("Invalid credentials. Use demo / demo.", "error");
          setStatus(loginStatus, "Invalid credentials.", "err");
        }
      });

      demoFillBtn.addEventListener("click", function () {
        document.getElementById("username").value = "demo";
        document.getElementById("password").value = "demo";
        setStatus(loginStatus, "Demo credentials filled.", "ok");
      });

      reloadBtn.addEventListener("click", function () {
        fetchSlots();
      });

      addSlotBtn.addEventListener("click", function () {
        addSlot();
      });

      addZoneBtn.addEventListener("click", function () {
        addZone();
      });

      renameZoneBtn.addEventListener("click", function () {
        renameZone();
      });

      deleteZoneBtn.addEventListener("click", function () {
        deleteZone();
      });

      slotToolToggle.addEventListener("click", function () {
        slotToolBody.classList.toggle("collapsed");
        slotToolToggle.textContent = slotToolBody.classList.contains("collapsed") ? "+" : "-";
      });

      zoneToolToggle.addEventListener("click", function () {
        zoneToolBody.classList.toggle("collapsed");
        zoneToolToggle.textContent = zoneToolBody.classList.contains("collapsed") ? "+" : "-";
      });

      slotSearch.addEventListener("input", function () {
        renderSlots();
      });

      slotSort.addEventListener("change", function () {
        renderSlots();
      });

      modalCancelBtn.addEventListener("click", function () {
        closeModal();
      });

      modalConfirmBtn.addEventListener("click", function () {
        if (typeof modalConfirmHandler === "function") {
          modalConfirmHandler();
        } else {
          closeModal();
        }
      });

      actionModal.addEventListener("click", function (event) {
        if (event.target === actionModal) {
          closeModal();
        }
      });

      document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && actionModal.classList.contains("active")) {
          closeModal();
        }
      });

      saveBtn.addEventListener("click", function () {
        saveAllSlots();
      });

      logoutBtn.addEventListener("click", function () {
        showLogin();
        setStatus(loginStatus, "Logged out successfully.", "ok");
      });

      themeToggle.addEventListener("click", function () {
        const dark = !document.body.classList.contains("dark");
        setTheme(dark);
      });

      initTheme();
      showLogin();
    })();
  </script>
</body>
</html>
