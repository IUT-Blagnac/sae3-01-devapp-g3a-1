'use strict';

document.addEventListener('DOMContentLoaded', function () {
    // Convertir les timestamps en objets Date
    const allData = {
        temperature: temperatureData,
        humidity: humidityData,
        activity: activityData,
        dioxidecarbon: dioxidecarbonData,
        tvoc: tvocData,
        illumination: illuminationData,
        infrared: infraredData,
        infraredVisible: infraredVisibleData,
        pressure: pressureData,
        timestamps: timestamps.map((ts) => new Date(ts))
    };

    // Données filtrées & groupées
    let filteredData = { ...allData };
    let currentPeriod = 'Mensuel'; // Par défaut

    /**
     * Génére le nom de fichier d'export
     * Format: [Période]-[DD]-[MM]-[YYYY]
     * Ex: "Mensuel-23-01-2025"
     */
    function getExportFilename(period) {
        const now = new Date();
        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();
        return `${period}-${day}-${month}-${year}`;
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // GROUPING FUNCTION
    // ─────────────────────────────────────────────────────────────────────────────
    function groupAndAverage(period) {
        const groupedData = {
            temperature: {},
            humidity: {},
            activity: {},
            dioxidecarbon: {},
            tvoc: {},
            illumination: {},
            infrared: {},
            infraredVisible: {},
            pressure: {}
        };
        const groupedTimestamps = {};

        // Décide la clé de regroupement selon la période
        const formatKey = (date) => {
            if (period === 'Aujourdhui') {
                // Regroupement horaire: ex. "13:00"
                return `${date.getHours()}:00`;
            } else if (period === 'Hebdomadaire' || period === 'Mensuel') {
                if (period === 'Mensuel') {
                    // "YYYY-MM"
                    const y = date.getFullYear();
                    const m = String(date.getMonth() + 1).padStart(2, '0');
                    return `${y}-${m}`;
                } else {
                    // Hebdomadaire => regrouper par jour "YYYY-MM-DD"
                    return date.toISOString().split('T')[0];
                }
            } else if (period === 'Annuel') {
                // Regrouper par année: "2025"
                return date.getFullYear().toString();
            }
        };

        // Remplit groupedData
        filteredData.timestamps.forEach((date, idx) => {
            const key = formatKey(date);

            if (!groupedData.temperature[key]) {
                groupedData.temperature[key] = [];
                groupedData.humidity[key] = [];
                groupedData.activity[key] = [];
                groupedData.dioxidecarbon[key] = [];
                groupedData.tvoc[key] = [];
                groupedData.illumination[key] = [];
                groupedData.infrared[key] = [];
                groupedData.infraredVisible[key] = [];
                groupedData.pressure[key] = [];
                groupedTimestamps[key] = date;
            }

            groupedData.temperature[key].push(filteredData.temperature[idx]);
            groupedData.humidity[key].push(filteredData.humidity[idx]);
            groupedData.activity[key].push(filteredData.activity[idx]);
            groupedData.dioxidecarbon[key].push(filteredData.dioxidecarbon[idx]);
            groupedData.tvoc[key].push(filteredData.tvoc[idx]);
            groupedData.illumination[key].push(filteredData.illumination[idx]);
            groupedData.infrared[key].push(filteredData.infrared[idx]);
            groupedData.infraredVisible[key].push(filteredData.infraredVisible[idx]);
            groupedData.pressure[key].push(filteredData.pressure[idx]);
        });

        // Calcule la moyenne
        const calculateAverage = (arr) => {
            return arr.reduce((sum, v) => sum + v, 0) / arr.length;
        };

        // Prépare la structure finale
        const averagedData = {
            temperature: [],
            humidity: [],
            activity: [],
            dioxidecarbon: [],
            tvoc: [],
            illumination: [],
            infrared: [],
            infraredVisible: [],
            pressure: [],
            timestamps: []
        };

        // Tri des clés (chronologique)
        const sortedKeys = Object.keys(groupedData.temperature).sort((a, b) => {
            if (period === 'Annuel') {
                // Années => compare en tant que nombres
                return parseInt(a) - parseInt(b);
            } else if (period === 'Mensuel') {
                // "YYYY-MM" => compare année, puis mois
                const [aY, aM] = a.split('-');
                const [bY, bM] = b.split('-');
                const aDate = new Date(parseInt(aY), parseInt(aM) - 1);
                const bDate = new Date(parseInt(bY), parseInt(bM) - 1);
                return aDate - bDate;
            } else {
                // "Aujourdhui"/"Hebdomadaire" => "YYYY-MM-DD"
                return new Date(a) - new Date(b);
            }
        });

        // Remplit averagedData
        sortedKeys.forEach((key) => {
            averagedData.temperature.push(calculateAverage(groupedData.temperature[key]));
            averagedData.humidity.push(calculateAverage(groupedData.humidity[key]));
            averagedData.activity.push(calculateAverage(groupedData.activity[key]));
            averagedData.dioxidecarbon.push(calculateAverage(groupedData.dioxidecarbon[key]));
            averagedData.tvoc.push(calculateAverage(groupedData.tvoc[key]));
            averagedData.illumination.push(calculateAverage(groupedData.illumination[key]));
            averagedData.infrared.push(calculateAverage(groupedData.infrared[key]));
            averagedData.infraredVisible.push(calculateAverage(groupedData.infraredVisible[key]));
            averagedData.pressure.push(calculateAverage(groupedData.pressure[key]));
            averagedData.timestamps.push(groupedTimestamps[key]);
        });

        return averagedData;
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // FILTER FUNCTION
    // ─────────────────────────────────────────────────────────────────────────────
    function filterData(period) {
        currentPeriod = period;
        const now = new Date();
        const todayString = now.toDateString();
        let cutoff = null;

        if (period === 'Aujourdhui') {
            // On garde seulement la date du jour
            cutoff = null;
        } else if (period === 'Hebdomadaire') {
            // 7 derniers jours
            cutoff = new Date(now.setDate(now.getDate() - 7));
        } else if (period === 'Mensuel') {
            // 12 derniers mois
            const twelveMonthsAgo = new Date();
            twelveMonthsAgo.setMonth(twelveMonthsAgo.getMonth() - 12);
            cutoff = twelveMonthsAgo;
        } else if (period === 'Annuel') {
            // On veut toutes les données => pas de cutoff
            cutoff = null;
        }

        // Filtre selon la cutoff
        const indices = allData.timestamps.map((date, idx) => {
            if (period === 'Aujourdhui') {
                // Même jour local
                return (date.toDateString() === todayString) ? idx : -1;
            }
            if (cutoff) {
                // date >= cutoff
                return (date >= cutoff) ? idx : -1;
            }
            // Annuel => tout
            return idx;
        }).filter((i) => i !== -1);

        // Construit le sous-ensemble filtré
        filteredData = {
            temperature: indices.map((i) => allData.temperature[i]),
            humidity: indices.map((i) => allData.humidity[i]),
            activity: indices.map((i) => allData.activity[i]),
            dioxidecarbon: indices.map((i) => allData.dioxidecarbon[i]),
            tvoc: indices.map((i) => allData.tvoc[i]),
            illumination: indices.map((i) => allData.illumination[i]),
            infrared: indices.map((i) => allData.infrared[i]),
            infraredVisible: indices.map((i) => allData.infraredVisible[i]),
            pressure: indices.map((i) => allData.pressure[i]),
            timestamps: indices.map((i) => allData.timestamps[i])
        };

        // Regroupe & moyenne
        filteredData = groupAndAverage(period);
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // RENDER CHART
    // ─────────────────────────────────────────────────────────────────────────────
    function renderChart() {
        // Format d'affichage des dates
        let xFormatOptions;
        switch (currentPeriod) {
            case 'Hebdomadaire':
                xFormatOptions = { day: '2-digit', month: '2-digit', year: '2-digit' };
                break;
            case 'Mensuel':
                xFormatOptions = { month: 'short', year: 'numeric' };
                break;
            case 'Annuel':
                xFormatOptions = { year: 'numeric' };
                break;
            default:
                // "Aujourdhui": jour + heure:minute
                xFormatOptions = {
                    day: '2-digit',
                    month: '2-digit',
                    year: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                break;
        }

        // Nom de fichier pour l'export
        const exportFilename = getExportFilename(currentPeriod);

        const options = {
            chart: {
                type: 'line',
                height: 350,
                toolbar: {
                    show: true,
                    export: {
                        csv: { filename: exportFilename },
                        svg: { filename: exportFilename },
                        png: { filename: exportFilename }
                    }
                }
            },
            colors: ['#e58a00', '#4680ff', '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#20c997'],
            dataLabels: { enabled: false },
            legend: {
                show: true,
                position: 'bottom'
            },
            markers: {
                size: 3,
                hover: { size: 5 }
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [
                { name: 'Température', data: filteredData.temperature },
                { name: 'Humidité', data: filteredData.humidity },
                { name: 'Activité', data: filteredData.activity },
                { name: 'CO2', data: filteredData.dioxidecarbon },
                { name: 'TVOC', data: filteredData.tvoc },
                { name: 'Illumination', data: filteredData.illumination },
                { name: 'Infrarouge', data: filteredData.infrared },
                { name: 'Infrarouge et Visible', data: filteredData.infraredVisible },
                { name: 'Pression', data: filteredData.pressure }
            ],
            xaxis: {
                categories: filteredData.timestamps.map((date) =>
                    date.toLocaleString('fr-FR', xFormatOptions)
                ),
                title: {
                    text: 'Date / Heure'
                }
            },
            yaxis: {
                title: {
                    text: 'Valeurs'
                },
                labels: {
                    formatter: function (value) {
                        // Arrondir à l’entier supérieur
                        return Math.ceil(value);
                    }
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    // Ajoute l'unité selon la série
                    formatter: function (value, { seriesIndex }) {
                        switch (seriesIndex) {
                            case 0: // Température
                                return value.toFixed(2) + ' °C';
                            case 1: // Humidité
                                return value.toFixed(2) + ' %';
                            case 2: // Activité
                                return value.toFixed(2); // sans unité
                            case 3: // TVOC
                                return value.toFixed(2) + ' ppb';
                            case 4: // Illumination
                                return value.toFixed(2) + ' lux';
                            case 5: // Infrarouge
                                return value.toFixed(2); // sans unité
                            case 6: // Infrarouge et Visible
                                return value.toFixed(2); // sans unité
                            case 7: // Pression
                                return value.toFixed(2) + ' hPa';
                            default:
                                return value.toFixed(2);
                        }
                    }
                }
            }
        };

        // Création / rendu du graphique
        const chartElement = document.querySelector('#revenue-sales-chart');
        chartElement.innerHTML = ''; // Nettoyer avant de recréer
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // INITIALISATION
    // ─────────────────────────────────────────────────────────────────────────────
    filterData('Mensuel'); // Par défaut
    renderChart();

    // Écouteur sur le sélecteur de période
    document.querySelector('.form-select').addEventListener('change', function (event) {
        const selectedPeriod = event.target.value; // 'Aujourdhui', 'Hebdomadaire', 'Mensuel', 'Annuel'
        filterData(selectedPeriod);
        renderChart();
    });
});
