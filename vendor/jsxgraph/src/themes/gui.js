JXG.Options = JXG.merge(JXG.Options, {

    //renderer: 'canvas',
    device: 'tablet',
    opacityLevel: 0.5,
    sensitive_area: 20,
    lastRegPolCorners: 4,

    lastSliderStart: -10,
    lastSliderEnd: 10,
    lastSliderIni: 1,

    board: {
        minimizeReflow: 'all', // 'svg', 'all', 'none'
        selection: {
            enabled: false
        }
    },

    angle: {
        fillColor: '#ddd',
        strokeColor: '#000',
        strokeWidth: 1,
        radius: 1.0,
        orthotype: 'sectordot'
    },

    axis: {
        ticks: {
            strokeColor: '#aaa',
            strokeOpacity: 0.4,
            label: {
                fontSize: 14,
                display: 'internal'
            }
        },
        label: {
            position: 'urt',
            offset: [-15, 30],
            display: 'internal'
        }
    },

    circle: {
        strokeColor: '#36f',
        strokeOpacity: 0.9,
        strokeWidth: 3
    },

    curve: {
        strokeWidth: 3,
        strokeOpacity: 0.9
    },

    glider : {
        strokeColor: 'orange',
        fillColor: 'orange',
        opacity: 1
    },

    intersection: {
        strokeColor: '#808080',
        fillColor: '#808080',
        opacity: 1
    },

    line: {
        //highlightStrokeOpacity: 0.3,
        strokeColor: '#36f',
        strokeOpacity: 0.9,
        strokeWidth: 3
    },

    midpoint: {
        strokeColor: '#808080',
        fillColor: '#808080',
        opacity: 1
    },

    point: {
        size: 4,
        fillColor:   '#cc0000',
        strokeColor: '#cc0000',
        strokeOpacity: 0.9,
        //fillOpacity: 0.7,
        highlightFillColor:   '#cc0000',
        highlightStrokeColor: '#cc0000',
        highlightFillOpacity: 0.4,
        highlightStrokeOpacity: 0.4,

        // snap on majorTicks

        snapX: -1,
        snapY: -1
    },

    polygon: {
        fillColor: '#ffff00',
        highlightFillColor: '#ffff00',
        hasInnerPoints: true,

        borders: {
            strokeColor: '#444444',
            strokeOpacity: 0.9,
            strokeWidth: 2
        }
    },

    precision: {
        touchMax: Infinity
    },

    sector: {
        strokeWidth: 0,
        highlightStrokeWidth: 0,
        arc: {
            visible: true,
            fillColor: 'none'
        }
    },

    segment: {
        label: {
            position: 'bot',
            offsets: [0,-12]
        }
    },

    slider: {
        highlightFillColor: '#ffffff',
        strokeOpacity: 0.5,
        strokeColor: '#444444',

        face: '[]',
        ticks: { tickEndings: [0, 1],
            minTicksDistance: 15,
            strokeColor: '#444444',
            strokeOpacity: 0.5,
            highlightStrokeColor: '#444444',
            strokeOpacity: 0.5,
            highlightStrokeOpacity: 0.5,
            needsRegularUpdate: true,
            fixed: false
            },
        baseline: {
            strokeColor: '#444444',
            highlightStrokeColor: '#444444',
            strokeOpacity: 0.5,
            highlightStrokeOpacity: 0.5,
            needsRegularUpdate: true,
            fixed: false
        },
        highline: {
            strokeColor: '#444444',
            highlightStrokeColor: '#444444',
            strokeOpacity: 0.5,
            highlightStrokeOpacity: 0.5,
            needsRegularUpdate: true
        },
        point1: {
            fixed: false,
            needsRegularUpdate: true,
            snapToGrid: true,
            strokeColor: '#444444',
            highlightStrokeColor: '#444444',
            fillColor: '#444444',
            highlightFillColor: '#444444',
            strokeOpacity: 0.5,
            highlightStrokeOpacity: 0.5,
            size: 3
        },
        point2: {
            fixed: false,
            needsRegularUpdate: true,
            snapToGrid: true,
            strokeColor: '#444444',
            highlightStrokeColor: '#444444',
            fillColor: '#444444',
            highlightFillColor: '#444444',
            strokeOpacity: 0.5,
            highlightStrokeOpacity: 0.5,
            size: 3
        }
    },

    tapemeasure: {
        strokeColor: '#000000',
        strokeWidth: 2,
        highlightStrokeColor: '#000000',
        strokeOpacity: 0.7,

        point1: {
            strokeOpacity: 0.7,
            snapToPoints: true,
            attractorUnit: 'screen',
            attractorDistance: 20
        },
        point2: {
            strokeOpacity: 0.7,
            snapToPoints: true,
            attractorUnit: 'screen',
            attractorDistance: 20
        },
        ticks: {
            strokeOpacity: 0.7
        }
    },

    text: {
        fontSize: 18,
        strokeColor: '#222',
        highlightStrokeColor: '#222',
        strokeOpacity: 1,
        highlightStrokeOpacity: 0.66666
    },

    trunclen: 2

/*
    line: {
        strokeColor: '#f00' // can't see red lines anymore for NOW ...
    },

    renderer: 'canvas'
*/
});

if (JXG.isAndroid() || JXG.isApple()) {
    JXG.Options.curve.RDPsmoothing = false;
    JXG.Options.curve.numberPointsHigh = 600;
    JXG.Options.curve.numberPointsLow = 100;
    JXG.Options.curve.doAdvancedPlot = true;
}
