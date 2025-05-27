export class StyleControls {
    constructor(container) {
        this.container = container;
        this.render();
    }

    render() {
        const styleControls = document.createElement('div');
        styleControls.className = 'control-group';
        styleControls.innerHTML = `
            <h4>Type de liens</h4>
            <select id="link-type">
                <option value="orthogonal" selected>Orthogonales</option>
                <option value="curves">Courbes</option>
                <option value="lines">Lignes droites</option>
            </select>

            <h4>Style des lignes</h4>
            <select id="line-style">
                <option value="solid">Ligne continue</option>
                <option value="dashed">Pointillés</option>
            </select>
            <div class="dash-speed-control" style="display: none;">
                <label>Vitesse de défilement:</label>
                <input type="range" id="dash-speed" min="0.1" max="3" step="0.1" value="1">
                <span id="dash-speed-value">1.0x</span>
            </div>

            <h4>Style des indicateurs</h4>
            <select id="arrow-style">
                <option value="arrow">Flèches</option>
                <option value="dot">Points</option>
                <option value="multiple-arrows">Flèches multiples</option>
                <option value="multiple-dots">Points multiples</option>
            </select>

            <h4>Couleurs des flèches</h4>
            <div class="direction-colors">
                <div>
                    <label>Direction positive:</label>
                    <input type="color" id="positive-direction-color" value="#ff4444">
                    <div class="color-box" style="background-color: #ff4444;"></div>
                </div>
                <div>
                    <label>Direction négative:</label>
                    <input type="color" id="negative-direction-color" value="#4444ff">
                    <div class="color-box" style="background-color: #4444ff;"></div>
                </div>
            </div>
        `;
        this.container.appendChild(styleControls);
    }

    getElements() {
        return {
            linkType: document.getElementById('link-type'),
            lineStyle: document.getElementById('line-style'),
            dashSpeed: document.getElementById('dash-speed'),
            dashSpeedValue: document.getElementById('dash-speed-value'),
            arrowStyle: document.getElementById('arrow-style'),
            positiveColor: document.getElementById('positive-direction-color'),
            negativeColor: document.getElementById('negative-direction-color')
        };
    }
} 
