<?php
require "./php/function.php";
?>

<?php

    // Get spawn delay 
    
    $x = $pdo->query("SELECT * FROM level WHERE id_level = 1 ");
    while ($post = $x-> fetch(PDO::FETCH_ASSOC)){
    // var_dump($post);
    $spawn_delay_1 = $post['spawn_delay'];
    $life_enemy1_1 = $post['enemy1'];
    $life_enemy2_1 = $post['enemy2'];
    $life_enemy3_1 = $post['enemy3'];


    }
    $x = $pdo->query("SELECT * FROM level WHERE id_level = 2 ");
    while ($post = $x-> fetch(PDO::FETCH_ASSOC)){
    // var_dump($post);
    $spawn_delay_2 = $post['spawn_delay'];
    $life_enemy1_2 = $post['enemy1'];
    $life_enemy2_2 = $post['enemy2'];
    $life_enemy3_2 = $post['enemy3'];
    }

    $x = $pdo->query("SELECT * FROM level WHERE id_level = 3 ");
    while ($post = $x-> fetch(PDO::FETCH_ASSOC)){
    // var_dump($post);
    $spawn_delay_3 = $post['spawn_delay'];
    $life_enemy1_3 = $post['enemy1'];
    $life_enemy2_3 = $post['enemy2'];
    $life_enemy3_3 = $post['enemy3'];
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link rel="stylesheet" href="./style/style.css">
    <title>Document</title>
</head>
<body>



<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">


<div id="container">
    <div class="btn_container">
    <button class="pause_btn"onclick="toggle()">
        <img src="./assets/components/pause.png" alt="pause"></button>
    </div> 
    <div>
    <a href="gameDispay.php?spawn_delay=<?= $spawn_delay_1 ?>&enemy1=<?= $life_enemy1_1 ?>&enemy2=<?= $life_enemy2_1 ?>&enemy3=<?= $life_enemy3_1 ?>">niveau1</a>
    <a href="gameDispay.php?spawn_delay=<?= $spawn_delay_2 ?>&enemy1=<?= $life_enemy1_2 ?>&enemy2=<?= $life_enemy2_2 ?>&enemy3=<?= $life_enemy3_2 ?>">niveau2</a>
    <a href="gameDispay.php?spawn_delay=<?= $spawn_delay_3 ?>&enemy1=<?= $life_enemy1_3 ?>&enemy2=<?= $life_enemy2_3 ?>&enemy3=<?= $life_enemy3_3 ?>">niveau3</a>

    </div>

    <canvas id="myCanvas1"></canvas>
   
</div>

<script>



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

let start = true;
let numberOfResources = 300;
let enemiesInterval = <?= $_GET['spawn_delay'] ?>;
let defendersInterval = 200;
let frame = 0;
let gameOver = false;
var pause = false;
let score = 0;

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
// console.log(towers);

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
 
       
            if (collisiontowers(enemies[j], towersdeux[0])){
            
                enemies[j].movement = 0;
                towersdeux[0].health -= enemies[j].damage ;
                if (towersdeux[0].health < 0) {
                    gameOver = true;
                }
    
        }}
        for (let d = 0; d < defenders.length; d++){
    
   
            if (collisiontowers2(  defenders[d], towersdeux[1])){
    
                defenders[d].movement = 0;
                towersdeux[1].health -=  defenders[d].damage ;
                
                
            }}
        // if (towerdeux.health
        
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
        const arrowType = [];
        const arrow = new Image();
        arrow.src = './assets/personnages/user_2/Arrow/Static.png';
        arrowType.push(arrow);
        ctx.drawImage(arrowType[0], this.x, this.y, 50, 10);
    }
}

//personnages

let chooseDefender1 = {
    x: 10,
    y: 10,
    width: 70,
    height: 85
}
const youChooseDefender1 = new Image();
youChooseDefender1.src = './assets/components/soldat-boloss.png';

function putchooseDefender1() {
    ctx.drawImage(youChooseDefender1, chooseDefender1.x, chooseDefender1.y, chooseDefender1.width, chooseDefender1.height);
}
let chooseDefender2 = {
    x: 90,
    y: 10,
    width: 70,
    height: 85
}
const youChooseDefender2 = new Image();
youChooseDefender2.src = './assets/components/soldat-archer.png';

function putchooseDefender2() {
    ctx.drawImage(youChooseDefender2, chooseDefender2.x, chooseDefender2.y, chooseDefender2.width, chooseDefender2.height);
}
let chooseDefender3 = {
    x: 170,
    y: 10,
    width: 70,
    height: 85
}
const youChooseDefender3 = new Image();
youChooseDefender3.src = './assets/components/soldat-roi.png';

function putchooseDefender3() {
    ctx.drawImage(youChooseDefender3, chooseDefender3.x, chooseDefender3.y, chooseDefender3.width, chooseDefender3.height);
}



function handleProjectiles() {
    for (let i = 0; i < projectiles.length; i++) {
        projectiles[i].update();
        projectiles[i].draw();
        if (collision(projectiles[i], towersdeux[1])) {
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


// DEFENDER

const defenderTypes = [];
const defender1 = new Image();
defender1.src = './assets/personnages/user_1/Run.png';
defenderTypes.push(defender1);
const defender2 = new Image();
defender2.src = './assets/personnages/user_2/Character/Run.png';
defenderTypes.push(defender2);
const defender3 = new Image();
defender3.src = './assets/personnages/user_3/Run.png';
defenderTypes.push(defender3);


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
        this.spriteWidthUser3 = 160;
        this.spriteHeightUser3 = 111;
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
            ctx.fillStyle = 'gold';
            ctx.font = '30px Orbitron';
            ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);
            ctx.drawImage(defenderTypes[0], this.frameX * this.spriteWidthUser1, 0, this.spriteWidthUser1, this.spriteHeightUser1, this.x -100 , this.y - 100, 300, 300);

        }
        if (this.chosenDefender === 2) {
            // console.log("caca");
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
            // console.log(this.shooting);
            handleProjectiles();
        }
            if (this.chosenDefender === 3) {
            
            ctx.fillStyle = 'gold';
            ctx.font = '30px Orbitron';
            ctx.fillText(Math.floor(this.health), this.x + 15, this.y + 30);
            ctx.drawImage(defenderTypes[2], this.frameX * this.spriteWidthUser3, 0, this.spriteWidthUser3, this.spriteHeightUser3, this.x - 80, this.y - 80, 274, 190);
        
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

            //   console.log(distTower);
            if (dist < 800 ) {
                defenders[i].shooting = true;
            } 
            if (distTower < 800 ) {
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

function spliceKilledEntitiesEnemies(enemies){
    returnedArrayEnemie = [];
    for (let i = 0; i < enemies.length; i++){
        if(enemies[i].health > 0){
            returnedArrayEnemie.push(enemies[i]);
        }
    }

    return returnedArrayEnemie;
}





function stopFiring(defenders, enemies){
    
    for (let i = 0; i < defenders.length; i++){
        for (let j = 0; j < enemies.length; j++){
            let dist = enemies[j].x - defenders[i].x   ;
            defenders.shooting = false;
            if(dist < 800 ){
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

const card3 = {
    x: 170,
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
    if (collision(mouse, card3) && mouse.clicked && can_click == true) {
        chosenDefender = 3;
        can_click = false;
        if (numberOfResources >= defender_cost ) {
            let verticalPosition = 4 * cellSize + cellGap;
            defenders.push(new Defender(verticalPosition, Math.random() * 2.4 + 2.9, 30, 500, defenderTypes[2]));
            // console.log(defenders);

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
    } else if (chosenDefender === 3) {
        card1stroke = 'black';
        card2stroke = 'black';
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
    let card3stroke = 'black';
    ctx.fillRect(card3.x, card3.y, card3.width, card3.height);
    // ctx.drawImage(defender2, 0, 0, 194, 194, 0, 5, 194/2, 194/2);
    ctx.strokeStyle = card3stroke;
    ctx.strokeRect( card3.x, card3.y, card3.width, card3.height);

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
        this.lifespan +=1;
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

        if (enemies[i].x < 0 ){
            gameOver = true;
            
        }
        if (enemies[i].health <= 0){
            let gainedResources = enemies[i].maxHealth/10;
            floatingMessages.push(new floatingMessage('+' + gainedResources, enemies[i].x, enemies[i].y,30, 'black'));
            numberOfResources += gainedResources;
            floatingMessages.push(new floatingMessage('+' + gainedResources, 700+positionX, 80, 30, 'gold'));
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
    if (frame % enemiesInterval === 0 ){
        let verticalPosition = 4 * cellSize + cellGap;
        if(Math.random()*100 > 90){
         
            enemies.push(new Enemy(verticalPosition, Math.random() * 2.4 + 2.9, 30, <?= $_GET['enemy3'] ?>, enemyTypes[2]));
        }
        else if(Math.random()*100 > 45){
            enemies.push(new Enemy(verticalPosition, Math.random() * 2.4 + 2.9, 10, <?= $_GET['enemy2'] ?>, enemyTypes[1]));
            if (this.shooting){
                this.timer++;
                this.movement = 0 ;
                // console.log(this.timer);
                if(this.timer % 50 === 0) {
                    projectiles.push(new Projectiles(this.x + 70, this.y + 50));
                }
            } else if (!this.shooting){
                this.movement = Math.random() * 2.4 + 2.9;
                // console.log("nice");
            }
        }
        else if(Math.random()*100 > 0){
            enemies.push(new Enemy(verticalPosition, Math.random() * 2.4 + 2.9, 10, <?= $_GET['enemy1'] ?>, enemyTypes[0]));
        }
        
        enemyPositions.push(verticalPosition);
        if (enemiesInterval > 120) enemiesInterval -= 50;
    }

}
//PAUSE



// utilities

function handleGameStatus(){
 
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
    if (towersdeux[1].health <= 0 ){
        ctx.fillStyle = 'black';
        ctx.font = '60px Orbitron';
        ctx.fillText('LEVEL COMPLETE', 130 + positionX, 300);
        ctx.font = '30px Orbitron';
        ctx.fillText('You win with ' + score + ' points!', 134 +positionX, 340);
        canvas.requestAnimationFrame(animate);
    }
}

function toggle() {
    pause = !pause;

}
  
function animate(){

    
    if(!pause) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    ctx.fillStyle = 'rgba(255, 115, 0, 0.2)';
    ctx.fillRect(0,0,controlsBar.width, controlsBar.height);
    handleTowers();
    handleGameGrid();
    
    handleTowers();
    handleDefenders(); 
    handleEnemies();
    chooseDefender();
    handleGameStatus();
    handleFloatingMessages();
    putchooseDefender1();
    putchooseDefender2();
    putchooseDefender3();
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




</script>  







<script>

</script>
</body>
</html>
