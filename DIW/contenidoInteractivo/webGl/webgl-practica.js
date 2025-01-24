// Seleccionar el canvas y obtener el contexto WebGL
const canvas = document.getElementById('webglCanvas');
const gl = canvas.getContext('webgl');

// Verificar si WebGL está disponible
if (!gl) {
    console.error("WebGL no está soportado en este navegador.");
    alert("Por favor, utiliza un navegador compatible con WebGL.");
}

// Vertex Shader
const vertexShaderSource = `
    attribute vec2 aPosition;
    void main() {
        gl_Position = vec4(aPosition, 0.0, 1.0);
    }
`;

// Fragment Shader
const fragmentShaderSource = `
    precision mediump float;
    void main() {
        gl_FragColor = vec4(1.0, 0.5, 0.0, 1.0); // Color naranja
    }
`;

// Función para compilar un shader
function createShader(gl, type, source) {
    const shader = gl.createShader(type);
    gl.shaderSource(shader, source);
    gl.compileShader(shader);

    if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
        console.error('Error al compilar el shader:', gl.getShaderInfoLog(shader));
        gl.deleteShader(shader);
        return null;
    }

    return shader;
}

// Compilar ambos shaders
const vertexShader = createShader(gl, gl.VERTEX_SHADER, vertexShaderSource);
const fragmentShader = createShader(gl, gl.FRAGMENT_SHADER, fragmentShaderSource);

// Crear el programa WebGL
function createProgram(gl, vertexShader, fragmentShader) {
    const program = gl.createProgram();
    gl.attachShader(program, vertexShader);
    gl.attachShader(program, fragmentShader);
    gl.linkProgram(program);

    if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
        console.error('Error al enlazar el programa:', gl.getProgramInfoLog(program));
        gl.deleteProgram(program);
        return null;
    }

    return program;
}

const program = createProgram(gl, vertexShader, fragmentShader);
gl.useProgram(program);

// Definir los vértices del triángulo
const vertices = new Float32Array([
    0.0,  0.5,  // Vértice superior
   -0.5, -0.5,  // Vértice inferior izquierdo
    0.5, -0.5   // Vértice inferior derecho
]);

// Crear y enlazar el buffer de posición
const positionBuffer = gl.createBuffer();
gl.bindBuffer(gl.ARRAY_BUFFER, positionBuffer);
gl.bufferData(gl.ARRAY_BUFFER, vertices, gl.STATIC_DRAW);

// Obtener la ubicación del atributo de posición
const positionAttributeLocation = gl.getAttribLocation(program, 'aPosition');
gl.enableVertexAttribArray(positionAttributeLocation);

// Especificar cómo leer los datos del buffer
gl.vertexAttribPointer(positionAttributeLocation, 2, gl.FLOAT, false, 0, 0);

// Configurar el color de limpieza del canvas
gl.clearColor(0.0, 0.0, 0.0, 1.0); // Negro
gl.clear(gl.COLOR_BUFFER_BIT);

// Dibujar el triángulo
gl.drawArrays(gl.TRIANGLES, 0, 3);
