
const canvas = document.getElementById('myCanvas1');
 
const container = document.getElementById('container');
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
let enemiesInterval = 1600;
let defendersInterval = 200;
let frame = 0;
let gameOver = false;
var pause = false;
let score = 0;
const winningScore = 50;
let chosenDefender = 1;


let defenders = [];
const enemies = [];
const enemyPositions = [];
const defenderPositions = [];
const projectiles = [];
const projectiles_enemy = [];
const resources = [];



// definition de la canvas size
canvas.width = 2500;
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


let canvasPosition = container.getBoundingClientRect();
// console.log(canvasPosition);
container.addEventListener('mousemove', function(e){
    mouse.x = e.x - canvasPosition.left;
    mouse.y = e.y - canvasPosition.top;
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

//pour que les elements en haut scroll en meme temps que le scroll 
const scrollDemo = document.querySelector("#container");
let positionX = 0;
scrollDemo.addEventListener("scroll", event => {
    positionX =  scrollDemo.scrollLeft;
});



//    GAME BOARD   

// blue control bar 
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

                       
// Towers
const towers = [];
const tower1 = new Image();
tower1.src = './assets/background/tour-gentil2.png';
towers.push(tower1);

const tower2 = new Image();
tower2.src = './assets/background/tour-mechant.png';
towers.push(tower2);
console.log(towers);

class tower {
    constructor(x, y, health, towerType) {
    this.x = x ,
    this.y = y,
    this.width = 248,
    this.height = 393,
    this.health = health
    this.towerType = towerType
} 
update() {
    this.health = 3000;
}

draw() {
    ctx.fillStyle = 'rgba(0,0,0,0.5)';
    ctx.fillRect(this.x, this.y, this.width, this.height);
    ctx.fillText(Math.floor(this.health), this.x , this.y);
    ctx.fillStyle = 'rgba(0,0,0,1)';
    ctx.fillStyle = 'black';
    ctx.font = '30px Orbitron';
    ctx.drawImage(this.towerType,this.x ,this.y ,this.width ,this.height )
    }
}

const towersdeux = [];

function handleTowers() {
    // towers.push(new tower(0 ,600 , 2000, towers[0] ));
    towersdeux.push(new tower(0 ,180 , 2000, towers[0] ));
    // towerdefender.draw();
    towersdeux.push(new tower(2252 ,180 , 2000, towers[1] ));
 
    towersdeux[0].draw();
    towersdeux[1].draw();

       
        // towerdefender.update();
    
        // towerenemy.draw();
    // for (let i = 0; i < towersdeux.length; i++){
        for (let j = 0; j < enemies.length; j++){
 
       
            if ( collisiontowers( enemies[j], towersdeux[0])){
            
                enemies[j].movement = 0;
                towersdeux[0].health -= enemies[j].damage ;
    
        }}
        for (let d = 0; d < defenders.length; d++){
    
   
            if (collisiontowers2(  defenders[d], towersdeux[1])){
    
                defenders[d].movement = 0;
                towersdeux[1].health -=  defenders[d].damage ;
                
            }}
        
        
    // }
}


// let tower_enemy = {
//     x: 200 ,
//     y: 600,
//     width: 400,
//     height: 600,
// }

// const tower2 = new Image();
// tower2.src = './assets/background/tour-mechant.png';

// function towers2() {
//     ctx.fillStyle = 'rgba(155,100,0,1)';
//     ctx.fillRect(tower_enemy.x, tower_enemy.y, tower_enemy.width, tower_enemy.height);
//     ctx.drawImage(tower2,2252 ,180 ,248 ,393 )
 
// }

//Projectiles

class Projectiles {
    constructor(x,y) {
        this.x = x;
        this.y = y;
        this.width = 10;
        this.height = 10;
        this.power = 50;
        this.speed = 10;
    }
    update() {
        this.x += this.speed;
    }
    draw() {
        ctx.fillStyle = "black";
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.width, 0, Math.PI * 2);
        ctx.fill();
    }
}

class Projectiles_enemy{
    constructor(x,y) {
        this.x = x;
        this.y = y;
        this.width = 10;
        this.height = 10;
        this.power = 20;
        this.speed = 10;
    }
    update() {
        this.x += this.speed;
    }
    draw() {
        ctx.fillStyle = "black";
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.width, 0, Math.PI * 2);
        ctx.fill();
    }
}
console.log(Projectiles.power);

function handleProjectiles_enemy() {
    for (let i = 0; i < projectiles_enemy.length; i++) {
        projectiles_enemy[i].update();
        projectiles_enemy[i].draw();
        for (let j = 0; j < enemies.length; j++) {
            if (enemies[j] && projectiles_enemy[i] && collision(projectiles_enemy[i], enemies[j])) {
                enemies[j].health -= projectiles_enemy[i].power;
                projectiles_enemy.splice(i,1);
            i--;
            }
        }
        if (projectiles_enemy[i] && projectiles_enemy[i].x > canvas.width - cellSize) {
            projectiles_enemy.splice(i,1);
            i--;
        }
    }
}

function handleProjectiles() {
    for (let i = 0; i < projectiles.length; i++) {
        projectiles[i].update();
        projectiles[i].draw();
        if ( collision(projectiles[i], towersdeux[1])) {
            towersdeux[1].health -= projectiles[i].power;
            projectiles.splice(i,1);
        i--;
        }
        for (let j = 0; j < enemies.length; j++) {
            if (enemies[j] && projectiles[i] && collision(projectiles[i], enemies[j])) {
                enemies[j].health -= projectiles[i].power;
                projectiles.splice(i,1);
            i--;
            }
           
        }
        if (projectiles[i] && projectiles[i].x > canvas.width - cellSize) {
            projectiles.splice(i,1);
            i--;
        }
    }
}

// life bar



// DENFENDER

// création des différents types d
const defenderTypes = [];
const defender1 = new Image();
defender1.src = './assets/personnages/user_1/Run.png';
defenderTypes.push(defender1);
const defender2 = new Image();
defender2.src = './assets/personnages/user_2/Character/Run.png';
defenderTypes.push(defender2);


class Defender {
    constructor(verticalPosition, movement, damage, health, defenserType){
        this.x = 0;
        this.y = verticalPosition;
        this.width = cellSize - cellGap * 2;
        this.height = cellSize - cellGap * 2;
        this.damage = damage;
        this.health = health;
        this.defenserType = defenserType;
        this.movement = movement;
        this.shooting = false;
        this.projectiles = [];



        this.maxHealth = this.health;
        this.chosenDefender = chosenDefender;
        this.defenderType = defenderTypes[0];
        this.frameX = 0;
        this.frameY = 0;
        this.minFrame = 0;
        this.maxFrame = 7;
        this.spriteWidthUser1 = 150;
        this.spriteHeightUser1 = 150;
        this.spriteWidthUser2 = 100;
        this.spriteHeightUser2 = 100;
        this.timer = 75;
    }

    update(){
        this.x += this.movement;

        if (frame % 10 === 0) {
            if (this.frameX < this.maxFrame) this.frameX++;
            else this.frameX = this.minFrame;
        }
    }
     draw(){
        if (this.chosenDefender === 1) {
            ctx.fillStyle = 'yellow';
            ctx.fillRect(this.x, this.y, this.width, this.height);
            ctx.fillStyle = 'gold';
            ctx.font = '30px Orbitron';
            ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);
            ctx.drawImage(this.defenderType, this.frameX * this.spriteWidthUser1, 0, this.spriteWidthUser1, this.spriteHeightUser1, this.x -100 , this.y - 100, 300, 300);

        }
        if (this.chosenDefender === 2) {
            ctx.fillStyle = 'pink';
            ctx.fillRect(this.x, this.y, this.width, this.height);
            ctx.fillStyle = 'gold';
            ctx.font = '30px Orbitron';
            ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);
            ctx.drawImage(defenderTypes[1], this.frameX * this.spriteWidthUser2, 0, this.spriteWidthUser2, this.spriteHeightUser2, this.x -60, this.y - 80, 240, 240);
            if (this.shooting){
                this.timer++;
                this.movement = 0 ;
                // console.log(this.shooting);
                if(this.timer % 100 === 0) {
                    projectiles.push(new Projectiles(this.x + 70, this.y + 50));
                }
            } else if (!this.shooting){
                this.movement = Math.random() * 2.4 + 2.9;
                // console.log("nice");
            }
            console.log(this.shooting);
            handleProjectiles();


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
            // defenders.splice(i, 1);
            // i--;
          }
          for (let j = 0; j < enemies.length; j++){
            //   console.log(defenders);
            //   console.log(i);
              let dist = enemies[j].x - defenders[i].x ;
              let distTower = towersdeux[1].x - defenders[i].x;
            //   console.log("distance" + dist );
            if (dist < 800 || distTower < 800 ) {
                defenders[i].shooting = true;
            } 
            if (defenders[i].health > 0 && collision(defenders[i], enemies[j])){
                enemies[j].movement = 0;
                defenders[i].movement = 0;
                defenders[i].health -= enemies[j].damage ;
                enemies[j].health -= defenders[i].damage;
                // console.log("speed" + enemies[j].movement);
            }
            
            if (defenders[i] && defenders[i].health <= 0){
                // defenders.splice(i, 1);
                // i--;
                enemies[j].movement = Math.random() * 2.4 + 2.9;

                // console.log(enemies[j].movement);

            } else if (enemies[j] && enemies[j].health <= 0) {
                defenders[i].shooting = false;

                defenders[i].movement = Math.random() * 2.4 + 2.9;

            }

        }

    }
    defenders = spliceKilledEntities(defenders);
    // if (frame % defendersInterval === 0 && score < winningScore){
    //     let verticalPosition = 4 * cellSize + cellGap;
    //     defenders.push(new Defender(verticalPosition));
    //     defenderPositions.push(verticalPosition);
        // if (defendersInterval > 120) defendersInterval += 50;
    //}
}

function spliceKilledEntities(defenders){
    returnedArray = [];
    for (let i = 0; i < defenders.length; i++){
        if(defenders[i].health > 0){
            returnedArray.push(defenders[i]);
        }
    }

    return returnedArray;
}





function stopFiring(defenders, enemies){
    
    for (let i = 0; i < defenders.length; i++){
        for (let j = 0; j < enemies.length; j++){
            let dist = enemies[j].x - defenders[i].x   ;
            defenders.shooting = false;
            if(dist < 800){
                defenders.shooting = true;
                break; 
            }
        }
    }
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

let can_click = true;

// avoid multi click (one click = when you release)
window.addEventListener('mouseup', ()=>{
    can_click = true;
});

function chooseDefender() {
    let defender_cost =100;
    let card1stroke = 'black';
    let card2stroke = 'black';
    // change le contour du perso selectionné

    if (collision(mouse, card1) && mouse.clicked && can_click == true) {
        
        chosenDefender = 1;
       
        can_click = false;
        if (numberOfResources >= defender_cost ) {
            let verticalPosition = 4 * cellSize + cellGap;
            defenders.push(new Defender(verticalPosition,Math.random() * 2.4 + 2.9, 30, 300, defenderTypes[0] ));

            numberOfResources -= defender_cost; 
        } else {
            floatingMessages.push(new floatingMessage('pas assez de ressources', mouse.x , mouse.y, 15, 'blue'));
        }
        
    } else if (collision(mouse, card2) && mouse.clicked && can_click == true) {
        chosenDefender = 2;
        // console.log(chosenDefender);
        can_click = false;
        if (numberOfResources >= defender_cost ) {
            let verticalPosition = 4 * cellSize + cellGap;
            defenders.push(new Defender(verticalPosition,Math.random() * 2.4 + 2.9, 0, 300, defenderTypes[1] ));
    
            
            numberOfResources -= defender_cost; 
        } else {
            floatingMessages.push(new floatingMessage('pas assez de ressources', mouse.x , mouse.y, 15, 'blue'));
        }
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
    ctx.fillRect(card1.x + positionX, card1.y, card1.width, card1.height);
    ctx.strokeStyle = card1stroke;
    ctx.strokeRect( card1.x + positionX, card1.y, card1.width, card1.height)
    // ctx.drawImage(defender1, 0, 0, 194, 194, 0, 5, 194/2, 194/2);
    ctx.fillRect(card2.x + positionX, card2.y, card2.width, card2.height);
    // ctx.drawImage(defender2, 0, 0, 194, 194, 0, 5, 194/2, 194/2);
    ctx.strokeStyle = card2stroke;
    ctx.strokeRect( card2.x + positionX, card2.y, card2.width, card2.height);

}

// Error message for rsources
const floatingMessages = [];
class floatingMessage { 
    constructor(value, x, y, size, color){
        this.value = value;
        this.x = x;
        this.y = y;
        this.size = size;
        this.lifeSpan = 0;
        this.color = color;
        this.opacity= 1;
    }
    update(){
        this.y -= 0.3;
        this.lefespan +=1;
        if(this.opacity > 0.01) this.opacity -= 0.01;
    }

    draw(){
        ctx.fillStyle = this.color;
        ctx.globalAlpha = this.opacity;
        ctx.globalAlpha = 1;
        ctx.font= this.size + 'px Orbitron';
        ctx.fillText(this.value, this.x, this.y);
    }

}

function handleFloatingMessages() {
    for (let i=0; i < floatingMessages.length; i++) {
        floatingMessages[i].update();
        floatingMessages[i].draw();
        if (floatingMessages[i].lifeSpan >= 50){
            floatingMessages.splice(i, 1);
            i--;
        }
    }
}


// enemies
const enemyTypes = [];
const enemy_weak = new Image();
enemy_weak.src = './assets/personnages/enemy_2/run.png';
enemyTypes.push(enemy_weak);

const enemy_shooter = new Image();
enemy_shooter.src = './assets/personnages/enemy_1/flight.png';
enemyTypes.push(enemy_shooter);

const enemy_strong = new Image();
enemy_strong.src = './assets/personnages/enemy_3/Move.png';
enemyTypes.push(enemy_strong);

class Enemy {
    constructor(verticalPosition, movement, damage, health, enemyType){
        this.x = canvas.width- 300;
        // this.x = 0;
        this.y = verticalPosition;
        this.width =  cellSize - cellGap * 2;
        this.height =  cellSize - cellGap *2;
        // this.speed = Math.random() * 0.4 + 0.9;
        this.movement = movement;
        this.damage = damage;
        this.health = health;
        this.maxHealth = this.health;
        // this.enemyType = enemyTypes[Math.floor(Math.random() * enemyTypes.length)];
        this.enemyType = enemyType
        this.frameX = 0;
        this.frameY = 0;
        this.minFrame = 0;
        this.maxFrame = 7;
        this.spriteWidth = 150;
        this.spriteHeight = 150;
        this.shooting = false;
        this.projectiles = [];
        this.timer = 49;

    }
    update(){
        this.x -= this.movement;
        if (frame % 10 === 0) {
            if (this.frameX < this.maxFrame) this.frameX++;
            else this.frameX = this.minFrame;
        }
        
    }

    draw(){
        ctx.fillStyle = 'red';
        ctx.fillRect(this.x, this.y, this.width, this.height);
        ctx.fillStyle = 'black';
        ctx.font = '30px Orbitron';
        ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);
        // ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh);
        ctx.drawImage(this.enemyType, this.frameX * this.spriteWidth, 0, this.spriteWidth, this.spriteHeight, this.x - 100, this.y- 100, 300, 300);

       
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
            stopFiring(defenders, enemies);

        }
        for (let j = 0; j < enemies.length; j++){
            if (enemies[i] && enemies[i].health <= 0){
                enemies.splice(i, 1);
                i--;
                defenders[j].movement = Math.random() * 2.4 + 2.9;
                // console.log(defenders[j].movement)
            }
        }

    }
    if (frame % enemiesInterval === 0 && score < winningScore){
        let verticalPosition = 4 * cellSize + cellGap;
        if(Math.random()*100 > 90){
         
            enemies.push(new Enemy(verticalPosition, Math.random() * 2.4 + 2.9, 30, 300, enemyTypes[2]));
        }
        else if(Math.random()*100 > 45){
            enemies.push(new Enemy(verticalPosition, Math.random() * 2.4 + 2.9, 10, 100, enemyTypes[1]));
            if (this.shooting){
                this.timer++;
                this.movement = 0 ;
                console.log(this.timer);
                if(this.timer % 50 === 0) {
                    projectiles.push(new Projectiles(this.x + 70, this.y + 50));
                }
            } else if (!this.shooting){
                this.movement = Math.random() * 2.4 + 2.9;
                // console.log("nice");
            }
            handleProjectiles_enemy();
            for (enemies_shoot = 0; enemies_shoot < enemies.length; enemies_shoot++) {
                
            }
        }
        else if(Math.random()*100 > 0){
            enemies.push(new Enemy(verticalPosition, Math.random() * 2.4 + 2.9, 10, 100, enemyTypes[0]));
        }
        
        enemyPositions.push(verticalPosition);
        if (enemiesInterval > 120) enemiesInterval -= 50;
    }

}
//PAUSE



// utilities

function handleGameStatus(){
    // ctx.fillStyle = 'red';
    // ctx.fillRect(this.x, this.y, this.width, this.height);
    // ctx.fillStyle = 'black';
    // ctx.font = '30px Orbitron';
    // ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);

    ctx.fillStyle = 'gold';
    
    ctx.font = '30px Orbitron';
    ctx.fillText('Score: ' + score,  450+positionX, 40);
   
    ctx.fillText('Resources: ' + numberOfResources, 450+positionX, 80);
    if (gameOver){
        ctx.fillStyle = 'black';
        ctx.font = '90px Orbitron';
        ctx.fillText('GAME OVER', 135+positionX, 330);
        setTimeout(function(){
            window.location.reload(1);
         }, 3000);
    
    }
    if (score >= winningScore && enemies.length === 0){
        ctx.fillStyle = 'black';
        ctx.font = '60px Orbitron';
        ctx.fillText('LEVEL COMPLETE', 130, 300);
        ctx.font = '30px Orbitron';
        ctx.fillText('You win with ' + score + ' points!', 134, 340);
    }
}

function toggle() {
    pause = !pause;}
  


function animate(){

    
    if(pause===false) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    ctx.fillStyle = 'rgba(255, 115, 0, 0.2)';
    ctx.fillRect(0,0,controlsBar.width, controlsBar.height);
    handleTowers();
    handleGameGrid();
   
    handleDefenders(); 
    handleEnemies();
    chooseDefender();
    handleGameStatus();
    handleFloatingMessages();
    frame++;
    }
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

function collisiontowers(first, second){
    if (    !(  first.x > second.x + second.width-100 ||
                first.x + first.width < second.x ||
                first.y > second.y + second.height ||
                first.y + first.height < second.y)
    ) {
        return true;
    };
};

function collisiontowers2(first, second){
    if (    !(  first.x > second.x + second.width +100  ||
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