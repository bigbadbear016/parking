<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Smart Parking Mission Control</title>
	<style>
		@import url("https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Sora:wght@600;700;800&display=swap");

		:root {
			--font-display: "Sora", "Segoe UI", Tahoma, sans-serif;
			--font-body: "Manrope", "Segoe UI", Tahoma, sans-serif;
			--space-1: 8px;
			--space-2: 16px;
			--space-3: 24px;
			--space-4: 32px;

			--bg: #eef3fb;
			--bg-accent: radial-gradient(circle at 0% 0%, #dfeeff 0%, transparent 35%),
				radial-gradient(circle at 100% 10%, #d8e7ff 0%, transparent 32%),
				radial-gradient(circle at 45% 100%, #e8f1ff 0%, transparent 36%),
				linear-gradient(160deg, #f8fbff 0%, #edf3fb 100%);
			--panel: rgba(250, 253, 255, 0.78);
			--panel-strong: rgba(255, 255, 255, 0.82);
			--text: #0f1e2b;
			--muted: #5d7184;
			--border: rgba(16, 37, 53, 0.12);
			--free: #21b076;
			--occ: #e95b5b;
			--maint: #8f96a3;
			--shadow-soft: 0 10px 30px rgba(23, 40, 59, 0.12);
			--shadow-card: 0 6px 20px rgba(10, 31, 49, 0.1);
			--progress-track: rgba(19, 42, 63, 0.1);
			--slot-bg: rgba(255, 255, 255, 0.58);
			--focus: 0 0 0 3px rgba(63, 119, 255, 0.24);
		}

		body.dark {
			--bg: #0a1320;
			--bg-accent: radial-gradient(circle at 5% 0%, #123e73 0%, transparent 40%),
				radial-gradient(circle at 95% 15%, #275892 0%, transparent 35%),
				radial-gradient(circle at 40% 100%, #11392f 0%, transparent 35%),
				linear-gradient(160deg, #070d17 0%, #0a1320 55%, #0c1727 100%);
			--panel: rgba(18, 29, 43, 0.58);
			--panel-strong: rgba(16, 27, 39, 0.72);
			--text: #e8f2ff;
			--muted: #91a7be;
			--border: rgba(186, 216, 255, 0.12);
			--free: #38d999;
			--occ: #ff7f7f;
			--maint: #a8b2c0;
			--shadow-soft: 0 14px 34px rgba(0, 0, 0, 0.4);
			--shadow-card: 0 10px 22px rgba(0, 0, 0, 0.28);
			--progress-track: rgba(189, 214, 241, 0.12);
			--slot-bg: rgba(26, 40, 58, 0.54);
			--focus: 0 0 0 3px rgba(146, 186, 255, 0.32);
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			min-height: 100vh;
			font-family: var(--font-body);
			color: var(--text);
			background: var(--bg);
			background-image: var(--bg-accent);
			transition: background 0.35s ease, color 0.35s ease;
		}

		.shell {
			max-width: 1260px;
			margin: 0 auto;
			padding: var(--space-4) var(--space-3) var(--space-4);
		}

		.header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: var(--space-2);
			padding: var(--space-3);
			background: var(--panel);
			border: 1px solid var(--border);
			border-radius: 22px;
			box-shadow: var(--shadow-soft);
			backdrop-filter: blur(16px);
			-webkit-backdrop-filter: blur(16px);
			margin-bottom: var(--space-3);
		}

		.title-wrap h1 {
			font-family: var(--font-display);
			font-size: clamp(1.35rem, 2.5vw, 1.85rem);
			letter-spacing: 0.02em;
			font-weight: 700;
		}

		.title-wrap p {
			margin-top: 6px;
			color: var(--muted);
			font-size: 0.95rem;
		}

		.theme-toggle {
			border: 1px solid var(--border);
			background: var(--panel-strong);
			color: var(--text);
			border-radius: 999px;
			display: inline-flex;
			align-items: center;
			gap: var(--space-1);
			min-height: 40px;
			padding: 8px 16px;
			font-weight: 700;
			font-size: 0.9rem;
			cursor: pointer;
			transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.3s ease;
		}

		.theme-toggle::before {
			content: "";
			width: 14px;
			height: 14px;
			border-radius: 999px;
			border: 2px solid currentColor;
			box-shadow: inset -4px 0 0 currentColor;
			opacity: 0.85;
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
			grid-template-columns: minmax(0, 1.6fr) minmax(280px, 0.9fr);
			gap: var(--space-3);
			align-items: start;
		}

		.panel {
			background: var(--panel);
			border: 1px solid var(--border);
			border-radius: 22px;
			box-shadow: var(--shadow-soft);
			backdrop-filter: blur(16px);
			-webkit-backdrop-filter: blur(16px);
		}

		.left-col {
			padding: var(--space-3);
			display: grid;
			gap: var(--space-3);
		}

		.hero {
			background: var(--panel-strong);
			border: 1px solid var(--border);
			border-radius: 18px;
			padding: var(--space-3);
			display: flex;
			flex-wrap: wrap;
			gap: var(--space-2);
			align-items: baseline;
			justify-content: space-between;
		}

		.hero h2 {
			font-family: var(--font-display);
			font-size: 1.15rem;
		}

		.hero p {
			color: var(--muted);
			font-size: 0.92rem;
		}

		.last-updated {
			font-size: 0.84rem;
			color: var(--muted);
			font-weight: 700;
		}

		.zone-grid {
			display: grid;
			gap: var(--space-2);
		}

		.zone {
			border: 1px solid var(--border);
			border-radius: 16px;
			padding: var(--space-2);
			background: var(--panel-strong);
			transition: transform 0.2s ease;
		}

		.zone:hover {
			transform: translateY(-2px);
		}

		.zone-head {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: var(--space-2);
			margin-bottom: var(--space-2);
			flex-wrap: wrap;
		}

		.zone-name {
			font-weight: 800;
			letter-spacing: 0.01em;
			font-size: 1rem;
		}

		.zone-meta {
			color: var(--muted);
			font-size: 0.85rem;
			font-weight: 700;
		}

		.slots {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
			gap: var(--space-1);
		}

		.slot-card {
			background: var(--slot-bg);
			border: 1px solid var(--border);
			border-radius: 14px;
			padding: var(--space-2);
			box-shadow: var(--shadow-card);
			transition: transform 0.2s ease, border-color 0.25s ease;
			position: relative;
			overflow: hidden;
			isolation: isolate;
			min-height: 112px;
		}

		.slot-card::after {
			content: "";
			position: absolute;
			inset: auto 0 0 0;
			height: 3px;
			z-index: -1;
			background: transparent;
			transition: background 0.3s ease;
		}

		.slot-card:hover {
			transform: translateY(-2px) scale(1.01);
		}

		.slot-id {
			font-family: var(--font-display);
			font-weight: 800;
			font-size: 1rem;
		}

		.status-badge {
			margin-top: 7px;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			border-radius: 999px;
			padding: 5px 10px;
			font-size: 0.78rem;
			font-weight: 800;
			letter-spacing: 0.02em;
			border: 1px solid transparent;
		}

		.slot-desc {
			margin-top: 10px;
			font-size: 0.84rem;
			line-height: 1.35;
			color: var(--muted);
		}

		.status-0 {
			border-color: color-mix(in srgb, var(--free) 42%, transparent);
		}

		.status-0 .status-badge {
			background: color-mix(in srgb, var(--free) 16%, transparent);
			color: var(--free);
			border-color: color-mix(in srgb, var(--free) 34%, transparent);
		}

		.status-0::after {
			background: var(--free);
		}

		.status-1 {
			border-color: color-mix(in srgb, var(--occ) 40%, transparent);
		}

		.status-1 .status-badge {
			background: color-mix(in srgb, var(--occ) 16%, transparent);
			color: var(--occ);
			border-color: color-mix(in srgb, var(--occ) 34%, transparent);
		}

		.status-1::after {
			background: var(--occ);
		}

		.status-2 {
			border-color: color-mix(in srgb, var(--maint) 48%, transparent);
		}

		.status-2 .status-badge {
			background: color-mix(in srgb, var(--maint) 18%, transparent);
			color: var(--maint);
			border-color: color-mix(in srgb, var(--maint) 34%, transparent);
		}

		.status-2::after {
			background: var(--maint);
		}

		.state-changed {
			animation: slotPulse 0.55s ease;
		}

		@keyframes slotPulse {
			0% {
				transform: scale(1);
			}
			35% {
				transform: scale(1.04);
			}
			100% {
				transform: scale(1);
			}
		}

		.right-col {
			padding: var(--space-3);
			display: grid;
			gap: var(--space-2);
			align-content: start;
			position: sticky;
			top: var(--space-3);
		}

		.panel-title {
			font-family: var(--font-display);
			font-size: 1.05rem;
			margin-bottom: 6px;
		}

		.stats {
			display: grid;
			gap: var(--space-1);
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}

		.stat {
			border: 1px solid var(--border);
			border-radius: 14px;
			padding: var(--space-2);
			background: var(--panel-strong);
			min-height: 95px;
		}

		.stat-label {
			color: var(--muted);
			font-size: 0.82rem;
			font-weight: 700;
		}

		.stat-value {
			margin-top: 8px;
			font-family: var(--font-display);
			font-size: 1.5rem;
			font-weight: 800;
			line-height: 1;
		}

		.stat-free .stat-value { color: var(--free); }
		.stat-occ .stat-value { color: var(--occ); }
		.stat-main .stat-value { color: var(--maint); }

		.availability {
			border: 1px solid var(--border);
			border-radius: 14px;
			background: var(--panel-strong);
			padding: var(--space-2);
		}

		.availability-head {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-bottom: var(--space-1);
		}

		.progress-track {
			width: 100%;
			height: 12px;
			border-radius: 999px;
			overflow: hidden;
			background: var(--progress-track);
			border: 1px solid var(--border);
		}

		.progress-fill {
			height: 100%;
			width: 0;
			background: linear-gradient(90deg, color-mix(in srgb, var(--free) 75%, #9ef0ca) 0%, var(--free) 100%);
			border-radius: inherit;
			transition: width 0.4s ease;
		}

		.availability-note {
			margin-top: 8px;
			color: var(--muted);
			font-size: 0.84rem;
		}

		.stagger {
			opacity: 0;
			transform: translateY(8px);
			animation: riseIn 0.45s ease forwards;
		}

		@keyframes riseIn {
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@media (max-width: 960px) {
			.layout {
				grid-template-columns: 1fr;
			}
			.right-col {
				order: -1;
				position: static;
			}
			.stats {
				grid-template-columns: repeat(4, minmax(0, 1fr));
			}
		}

		@media (max-width: 680px) {
			.shell {
				padding: var(--space-2);
			}
			.header,
			.left-col,
			.right-col {
				padding: var(--space-2);
			}
			.theme-toggle {
				width: 100%;
				justify-content: center;
			}
			.stats {
				grid-template-columns: repeat(2, minmax(0, 1fr));
			}
			.slots {
				grid-template-columns: repeat(2, minmax(0, 1fr));
			}
			.hero {
				flex-direction: column;
				align-items: flex-start;
			}
		}
	</style>
</head>
<body>
	<div class="shell">
		<header class="header stagger" style="animation-delay: 0.03s;">
			<div class="title-wrap">
				<h1>Smart Parking Mission Control</h1>
				<p>Live zone visibility, occupancy intelligence, and operational clarity.</p>
			</div>
			<button id="themeToggle" class="theme-toggle" type="button" aria-label="Toggle theme">Toggle Theme</button>
		</header>

		<main class="layout">
			<section class="panel left-col stagger" style="animation-delay: 0.1s;">
				<div class="hero">
					<div>
						<h2>Real-Time Slot Monitoring</h2>
						<p>Auto-refreshing every second from API telemetry.</p>
					</div>
					<div id="lastUpdated" class="last-updated">Waiting for first update...</div>
				</div>

				<div id="zones" class="zone-grid" aria-live="polite"></div>
			</section>

			<aside class="panel right-col stagger" style="animation-delay: 0.16s;">
				<h2 class="panel-title">Live Statistics</h2>
				<div class="stats">
					<article class="stat stat-free">
						<p class="stat-label">Total Free</p>
						<p class="stat-value" id="statFree">0</p>
					</article>
					<article class="stat stat-occ">
						<p class="stat-label">Total Occupied</p>
						<p class="stat-value" id="statOccupied">0</p>
					</article>
					<article class="stat stat-main">
						<p class="stat-label">Maintenance</p>
						<p class="stat-value" id="statMaintenance">0</p>
					</article>
					<article class="stat">
						<p class="stat-label">Utilization</p>
						<p class="stat-value" id="statUtilization">0%</p>
					</article>
				</div>

				<section class="availability">
					<div class="availability-head">
						<h3 class="panel-title">Availability</h3>
						<strong id="availabilityPct">0%</strong>
					</div>
					<div class="progress-track">
						<div id="progressFill" class="progress-fill"></div>
					</div>
					<p class="availability-note" id="availabilityNote">0 of 0 slots currently free.</p>
				</section>
			</aside>
		</main>
	</div>

	<script>
		(function () {
			const apiUrl = "api.php";
			const refreshMs = 1000;
			const previousStates = new Map();

			const zoneContainer = document.getElementById("zones");
			const statFree = document.getElementById("statFree");
			const statOccupied = document.getElementById("statOccupied");
			const statMaintenance = document.getElementById("statMaintenance");
			const statUtilization = document.getElementById("statUtilization");
			const availabilityPct = document.getElementById("availabilityPct");
			const availabilityNote = document.getElementById("availabilityNote");
			const progressFill = document.getElementById("progressFill");
			const lastUpdated = document.getElementById("lastUpdated");
			const themeToggle = document.getElementById("themeToggle");

			const stateMeta = {
				0: {
					label: "Available",
					desc: "Ready for incoming vehicles.",
					css: "status-0"
				},
				1: {
					label: "Occupied",
					desc: "Currently occupied by a vehicle.",
					css: "status-1"
				},
				2: {
					label: "Maintenance",
					desc: "Temporarily unavailable for service.",
					css: "status-2"
				}
			};

			function applySavedTheme() {
				const saved = localStorage.getItem("parking-theme");
				const preferredDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
				const dark = saved ? saved === "dark" : preferredDark;
				document.body.classList.toggle("dark", dark);
				themeToggle.textContent = dark ? "Switch to Light" : "Switch to Dark";
			}

			function toggleTheme() {
				const dark = !document.body.classList.contains("dark");
				document.body.classList.toggle("dark", dark);
				localStorage.setItem("parking-theme", dark ? "dark" : "light");
				themeToggle.textContent = dark ? "Switch to Light" : "Switch to Dark";
			}

			function groupByZone(slots) {
				const grouped = {};
				Object.keys(slots)
					.sort((a, b) => a.localeCompare(b, undefined, { numeric: true, sensitivity: "base" }))
					.forEach((slotId) => {
						const zone = (slotId.match(/^[A-Za-z]+/) || ["Unknown"])[0].toUpperCase();
						if (!grouped[zone]) {
							grouped[zone] = [];
						}
						grouped[zone].push({ id: slotId, state: Number(slots[slotId]) });
					});
				return grouped;
			}

			function renderZones(slots) {
				const grouped = groupByZone(slots);
				const zoneKeys = Object.keys(grouped);
				zoneContainer.innerHTML = "";

				zoneKeys.forEach((zoneKey) => {
					const zoneSlots = grouped[zoneKey];
					const zoneEl = document.createElement("section");
					zoneEl.className = "zone";

					const freeInZone = zoneSlots.filter((slot) => slot.state === 0).length;
					const occupiedInZone = zoneSlots.filter((slot) => slot.state === 1).length;
					const maintenanceInZone = zoneSlots.filter((slot) => slot.state === 2).length;

					const head = document.createElement("header");
					head.className = "zone-head";
					head.innerHTML =
						"<span class=\"zone-name\">Zone " + zoneKey + "</span>" +
						"<span class=\"zone-meta\">Free: " + freeInZone +
						" | Occupied: " + occupiedInZone +
						" | Maintenance: " + maintenanceInZone + "</span>";
					zoneEl.appendChild(head);

					const slotWrap = document.createElement("div");
					slotWrap.className = "slots";

					zoneSlots.forEach((slot) => {
						const meta = stateMeta[slot.state] || stateMeta[2];
						const card = document.createElement("article");
						card.className = "slot-card " + meta.css;

						if (previousStates.has(slot.id) && previousStates.get(slot.id) !== slot.state) {
							card.classList.add("state-changed");
						}

						card.innerHTML =
							"<h4 class=\"slot-id\">" + slot.id + "</h4>" +
							"<span class=\"status-badge\">" + meta.label + "</span>" +
							"<p class=\"slot-desc\">" + meta.desc + "</p>";

						slotWrap.appendChild(card);
						previousStates.set(slot.id, slot.state);
					});

					zoneEl.appendChild(slotWrap);
					zoneContainer.appendChild(zoneEl);
				});
			}

			function renderStats(slots) {
				const values = Object.values(slots).map(Number);
				const total = values.length;
				const free = values.filter((state) => state === 0).length;
				const occupied = values.filter((state) => state === 1).length;
				const maintenance = values.filter((state) => state === 2).length;

				const utilization = total === 0 ? 0 : (occupied / total) * 100;
				const availability = total === 0 ? 0 : (free / total) * 100;

				statFree.textContent = String(free);
				statOccupied.textContent = String(occupied);
				statMaintenance.textContent = String(maintenance);
				statUtilization.textContent = utilization.toFixed(1) + "%";

				availabilityPct.textContent = availability.toFixed(1) + "%";
				progressFill.style.width = availability.toFixed(1) + "%";
				availabilityNote.textContent = free + " of " + total + " slots currently free.";
			}

			function stampUpdatedTime() {
				const now = new Date();
				lastUpdated.textContent = "Last update: " + now.toLocaleTimeString();
			}

			async function refreshData() {
				try {
					const response = await fetch(apiUrl, { cache: "no-store" });
					if (!response.ok) {
						throw new Error("HTTP " + response.status);
					}

					const data = await response.json();
					const slots = data && data.slots ? data.slots : {};

					renderZones(slots);
					renderStats(slots);
					stampUpdatedTime();
				} catch (error) {
					lastUpdated.textContent = "Update failed: " + error.message;
				}
			}

			themeToggle.addEventListener("click", toggleTheme);
			applySavedTheme();
			refreshData();
			setInterval(refreshData, refreshMs);
		})();
	</script>
</body>
</html>
