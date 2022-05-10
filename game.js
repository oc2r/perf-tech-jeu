const canvas = document.getElementById('myCanvas1');
const ctx = canvas.getContext('2d');

// const container = document.getElementById('container');
// container.width = 900;
// container.height = 600;

// definition de la canvas size
canvas.width = 2000;
canvas.height = 600;


// mouse parametres
const mouse = {
    x: 10,
    y: 10,
    width: 0.1,
    height: 0.1,
}
// definir dimension de la convas pour la souris
let canvasPosition = canvas.getBoundingClientRect();
canvas.addEventListener('mousemove', function(e){
    mouse.x = e.x - canvasPosition.left;
    mouse.y = e.y - canvasPosition.top;
});

// grid variables
const cellSize = 100;
const cellGap = 3;
const gameGrid = [];

// barre de control
const controlsBar = {
    width: canvas.width,
    height: cellSize,
}
class Cell {
    constructor(x, y){
        this.x = x;
        this.y = y;
        this.width = cellSize;
        this.height = cellSize;
    }
    draw(){
      
            ctx.strokeStyle = 'black';
            ctx.strokeRect(this.x, this.y, this.width, this.height);
        
    }
}

// Creation d'une grid composée de cells
function createGrid(){
    // creation des cells verticales : ajouter cell tant que la hauteur de la canvas n'est pas "remplie"
    for (let y = cellSize; y < canvas.height; y += cellSize){
        // creation des cells horizontales : ajouter cell tant que la largeur de la canvas n'est pas "remplie"
        for (let x = 0; x < canvas.width; x += cellSize){
            gameGrid.push(new Cell(x, y));
        }
    }
}
createGrid();

// Dessiner chaque cell
function handleGameGrid(){
    for (let i = 0; i < gameGrid.length; i++){
        gameGrid[i].draw();
    }
}


// Animation : appeler ici toute fonction qui necessitent d'etre animée
function animate(){
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = 'blue';
    ctx.fillRect(0,0,controlsBar.width, controlsBar.height);
    handleGameGrid();
    requestAnimationFrame(animate);
}
animate();