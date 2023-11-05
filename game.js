const gameTextElement = document.getElementById('game-text');
const gameImageElement = document.getElementById('game-image');
const gameOptionsElement = document.getElementById('game-options');

let scenes = [];
let currentScene = 0;

async function loadScenesFromFile() {
    try {
        const response = await fetch('scenes.json');
        scenes = await response.json();
        loadScene(currentScene);
    } catch (err) {
        console.error('Failed to load scenes', err);
    }
}

function loadScene(sceneIndex) {
    const scene = scenes[sceneIndex];
    if (!scene) return;

    gameTextElement.innerText = scene.text;
    gameImageElement.src = scene.image;
    gameOptionsElement.innerHTML = '';
    scene.options.forEach(option => {
        const button = document.createElement('button');
        button.innerText = option.text;
        button.addEventListener('click', () => loadScene(option.nextScene));
        gameOptionsElement.appendChild(button);
    });
}

loadScenesFromFile();
