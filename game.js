const canvas = document.getElementById('myCanvas1');
const ctx = canvas.getContext('2d');

// const container = document.getElementById('container');
// container.width = 900;
// container.height = 600;
  
// global variables

    // grid variables
    const cellSize = 100;
    const cellGap = 3;
    const gameGrid = [];

let numberOfResources = 300;
let enemiesInterval = 600;
let defendersInterval = 200;
let frame = 0;
let gameOver = false;
let score = 0;
const winningScore = 50;
let chosenDefender = 1;

const defenders = [];
const enemies = [];
const enemyPositions = [];
const defenderPositions = [];
const projectiles = [];
const resources = [];



// definition de la canvas size
canvas.width = 2000;
canvas.height = 600;

// mouse
const mouse = {
    x: 10,
    y: 10,
    width: 0.1,
    height: 0.1,
    clicked : false,
};

canvas.addEventListener('mousedown', function(){
    mouse.clicked = true;
});

canvas.addEventListener('mouseup', function(){
    mouse.clicked = false;

});


let canvasPosition = canvas.getBoundingClientRect();
canvas.addEventListener('mousemove', function(e){
    mouse.x = e.x - canvasPosition.left;
    mouse.y = e.y - canvasPosition.top;
});
canvas.addEventListener('mouseleave', function(){
    mouse.y = undefined;
    mouse.y = undefined;
});

// var   derniere_position_de_scroll_connue = 100;

// let getContainer = document.getElementById('container');
// getContainer.addEventListener("scroll", ()=> {
//     derniere_position_de_scroll_connue = getContainer.scrollX;

// } );


canvas.addEventListener('mouseleave', function(){
    mouse.y = undefined;
    mouse.y = undefined;
});


//    GAME BOARD   

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
        if (mouse.x && mouse.y && collision(this, mouse)){
            ctx.strokeStyle = 'black';
            ctx.strokeRect(this.x, this.y, this.width, this.height);
        }
    }
}
function createGrid(){
    for (let y = cellSize; y < canvas.height; y += cellSize){
        for (let x = 0; x < canvas.width; x += cellSize){
            gameGrid.push(new Cell(x, y));
        }
    }
}
createGrid();
function handleGameGrid(){
    for (let i = 0; i < gameGrid.length; i++){
        gameGrid[i].draw();
    }
}


// defenders
const defender1 = new Image();
defender1.src = "defender1.png";
const defender2 = new Image();
defender2.src = "defender2.png";

class Defender {
    constructor(verticalPosition){
        this.x = 0;
        this.y = verticalPosition;
        this.width = cellSize - cellGap * 2;
        this.height = cellSize - cellGap * 2;
        this.speed = Math.random() * 0.2 + 0.4;
        this.movement = this.speed;
        this.health = 100;
        this.maxHealth = this.health;
        this.chosenDefender = chosenDefender;
    }
    update(){
        this.x += this.movement;
    }
    draw(){
        // ctx.fillStyle = 'blue';
        // ctx.fillRect(this.x, this.y, this.width, this.height);
        // ctx.fillStyle = 'gold';
        // ctx.font = '30px Orbitron';
        // ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);
        if (this.chosenDefender === 1) {
            ctx.fillStyle = 'yellow';
            ctx.fillRect(this.x, this.y, this.width, this.height);
            ctx.fillStyle = 'gold';
            ctx.font = '30px Orbitron';
            ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);
        }
        if (this.chosenDefender === 2) {
            ctx.fillStyle = 'pink';
            ctx.fillRect(this.x, this.y, this.width, this.height);
            ctx.fillStyle = 'gold';
            ctx.font = '30px Orbitron';
            ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);
        
        }
    }
}
function handleDefenders(){
    for (let i = 0; i < defenders.length; i++){
        defenders[i].update();
        defenders[i].draw();
        if (defenders[i].health <= 0){
            let gainedResources = defenders[i].maxHealth/10;
            numberOfResources += gainedResources;
            score += gainedResources;
            const findThisIndex = defenderPositions.indexOf(defenders[i].y);
            defenderPositions.splice(findThisIndex, 1);
            defenders.splice(i, 1);
            i--;
          }
          for (let j = 0; j < enemies.length; j++){
            if (defenders[i] && collision(defenders[i], enemies[j])){
                enemies[j].movement = 0;
                defenders[i].movement = 0;
                defenders[i].health -= 1;
                enemies[j].health -= 1;

            }
            if (defenders[i] && defenders[i].health <= 0){
                defenders.splice(i, 1);
                i--;
                enemies[j].movement = enemies[j].speed;
            }
        }
    }
    // if (frame % defendersInterval === 0 && score < winningScore){
    //     let verticalPosition = 4 * cellSize + cellGap;
    //     defenders.push(new Defender(verticalPosition));
    //     defenderPositions.push(verticalPosition);
        // if (defendersInterval > 120) defendersInterval += 50;
    //}
}

const card1 = {
    x: 10,
    y: 10,
    width: 70,
    height: 85
}

const card2 = {
    x: 90,
    y: 10,
    width: 70,
    height: 85
}



function chooseDefender() {
    let defender_cost =100;
    let card1stroke = 'black';
    let card2stroke = 'black';
    // change le contour du perso selectionné
    if (collision(mouse, card1) && mouse.clicked) {
        chosenDefender = 1;
        console.log(chosenDefender);
        if (numberOfResources >= defender_cost ) {
            let verticalPosition = 4 * cellSize + cellGap;
            defenders.push(new Defender(verticalPosition));
    
            
            numberOfResources -= defender_cost; 
        }
        
    } else if (collision(mouse, card2) && mouse.clicked) {
        chosenDefender = 2;
        console.log(chosenDefender);
        if (numberOfResources >= defender_cost ) {
            let verticalPosition = 4 * cellSize + cellGap;
            defenders.push(new Defender(verticalPosition));
    
            
            numberOfResources -= defender_cost; 
        };
    }

    if (chosenDefender === 1) {
        card1stroke = 'gold';
        card2stroke = 'black';
    } else if (chosenDefender === 2) {
        card1stroke = 'black';
        card2stroke = 'gold';
    } else {
        card1stroke = 'black';
        card2stroke = 'black';
    }

    ctx.lineWidth = 1;
    ctx.fillStyle = 'rgba(0,0,0,0.2)';
    ctx.fillRect(card1.x, card1.y, card1.width, card1.height);
    ctx.strokeStyle = card1stroke;
    ctx.strokeRect( card1.x, card1.y, card1.width, card1.height)
    // ctx.drawImage(defender1, 0, 0, 194, 194, 0, 5, 194/2, 194/2);
    ctx.fillRect(card2.x, card2.y, card2.width, card2.height);
    // ctx.drawImage(defender2, 0, 0, 194, 194, 0, 5, 194/2, 194/2);
    ctx.strokeStyle = card2stroke;
    ctx.strokeRect( card2.x, card2.y, card2.width, card2.height);

}

// const floatingMessages = [];
// class floatingMessages { 
//     constructor(value, x, y, size, color){
//         this.value = value;
//         this.x = x;
//         this.y = y;
//         this.size = size;
//         this.lifeSpan = 0;
//         this.color = color;
//         this.opacity= 1;
//     }
//     update(){
//         this.y -= 0.3;
//         this.lefespan +=1;
//         if(this.opacity > 0.01) this.opacity -= 0.01;
//     }

//     draw(){
//         ctx.fillStyle = this.color;
//         ctx.globalAlpha = this.opacity;
//         ctx.globalAlpha = 1;
//     }

// }


// enemies

class Enemy {
    constructor(verticalPosition){
        this.x = canvas.width;
        // this.x = 0;
        this.y = verticalPosition;
        this.width = cellSize - cellGap * 2;
        this.height = cellSize - cellGap * 2;
        this.speed = Math.random() * 0.2 + 0.4;
        this.movement = this.speed;
        this.health = 100;
        this.maxHealth = this.health;
        this.chosenDefender = chosenDefender;
    }
    update(){
        this.x -= this.movement;
    }
    draw(){
        ctx.fillStyle = 'red';
        ctx.fillRect(this.x, this.y, this.width, this.height);
        ctx.fillStyle = 'black';
        ctx.font = '30px Orbitron';
        ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);
    }
}
function handleEnemies(){
    for (let i = 0; i < enemies.length; i++){
        enemies[i].update();
        enemies[i].draw();
        if (enemies[i].x < 0){
            gameOver = true;
        }
        if (enemies[i].health <= 0){
            let gainedResources = enemies[i].maxHealth/10;
            numberOfResources += gainedResources;
            score += gainedResources;
            const findThisIndex = enemyPositions.indexOf(enemies[i].y);
            enemyPositions.splice(findThisIndex, 1);
            enemies.splice(i, 1);
            i--;
          }
    }
    if (frame % enemiesInterval === 0 && score < winningScore){
        let verticalPosition = 4 * cellSize + cellGap;
        enemies.push(new Enemy(verticalPosition));
        enemyPositions.push(verticalPosition);
        if (enemiesInterval > 120) enemiesInterval -= 50;
    }
}

// utilities

function handleGameStatus(){
    // ctx.fillStyle = 'red';
    // ctx.fillRect(this.x, this.y, this.width, this.height);
    // ctx.fillStyle = 'black';
    // ctx.font = '30px Orbitron';
    // ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);

    ctx.fillStyle = 'gold';
    
    ctx.font = '30px Orbitron';
    ctx.fillText('Score: ' + score,  450, 40);
   
    ctx.fillText('Resources: ' + numberOfResources, 450, 80);
    if (gameOver){
        ctx.fillStyle = 'black';
        ctx.font = '90px Orbitron';
        ctx.fillText('GAME OVER', 135, 330);
    }
    if (score >= winningScore && enemies.length === 0){
        ctx.fillStyle = 'black';
        ctx.font = '60px Orbitron';
        ctx.fillText('LEVEL COMPLETE', 130, 300);
        ctx.font = '30px Orbitron';
        ctx.fillText('You win with ' + score + ' points!', 134, 340);
    }
}

function animate(){
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    ctx.fillStyle = 'blue';
    ctx.fillRect(0,0,controlsBar.width, controlsBar.height);
    handleGameGrid();
  
    handleDefenders();
    chooseDefender();
    handleEnemies();
    handleGameStatus();
    frame++;
    if (!gameOver) requestAnimationFrame(animate);
}
animate();

function collision(first, second){
    if (    !(  first.x > second.x + second.width ||
                first.x + first.width < second.x ||
                first.y > second.y + second.height ||
                first.y + first.height < second.y)
    ) {
        return true;
    };
};


// coucou
window.addEventListener('resize', function(){
    canvasPosition = canvas.getBoundingClientRect();
})

