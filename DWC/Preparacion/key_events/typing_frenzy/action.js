// Typing Frenzy - Scoring System
// ================================= =====
// Each correct letter typed:        10pts
// Each word completed (per letter): 25pts
// End of level time bonus (per s):  15pts * difficulty factor (/ timePerLetter)
//                                         * difficulty level (1-3)
var roundName = ['Mammals', 'Intelligent Creatures', 'Cosmic!', 'Biology', 'Fruit', 'Mental', 'HTML to JavaScript'];
var wordList = [ ['antelope', 'bat', 'leopard', 'whale', 'gorilla'],
                 ['crow', 'pigeon', 'bonobo', 'octopus', 'parrot', 'elephant', 'chimpanzee', 'raven', 'dolphin', 'human'],
                 ['galaxy', 'supernova', 'aurora', 'planet', 'spacetime', 'nebula', 'asteroid', 'moon', 'orbit', 'comet'],
                 ['anatomy', 'cell', 'protein', 'chromosome', 'photosynthesis', 'mitosis', 'muscle', 'bacterium', 'blood', 'peristalsis'],
                 ['avocado', 'banana', 'persimmon', 'melon', 'pomegranate', 'almond', 'kumquat', 'lime', 'tomato', 'coffee'],
                 ['brain', 'thought', 'psychology', 'endorphin', 'axon', 'depression', 'synapse', 'euphoria', 'neuron', 'stimulant'],
                 ['tag', 'element', 'attribute', 'property', '!doctype', 'DOM', 'flexbox', 'transition', 'addEventListener', 'callback'] ];
var d = [1, 0.5, 0.25];
var nextWord, wordNumber, difficulty, countdownTimer,
    timePerLetter, startTime, timeAllowed, timeLeft, rt, score;
var level = 0;
var countdownLoop = 0;

addArrowKeys(); // Event listeners to respond to arrow keys

// Start game
//
function go(diff) {
  document.querySelector('#go').style.display = 'none';
  document.querySelector('#endGameDisplay').style.display = 'none';
  difficulty = diff;
  level = 0;
  document.querySelector('#prompt').textContent = 'Round 1: ' + roundName[0];
  countdown(); // Countdown into the game
}
//
// Trigger 3 second intro countdown
function countdown() {
  document.querySelector('#word').style.display = 'none';
  document.querySelector('#timeLeft').style.display = 'none';
  document.querySelector('#timeDisplay').style.display = 'none';
  if (level == 0) {document.querySelector('#scoreBoard').style.display = 'none';}
  updateScore(); // Ensure time bonus is refelected prior to start of next round
  countdownUpdate();
  countdownTimer = setInterval(countdownUpdate, 100);
}
function countdownUpdate() {
  var countdown = document.querySelector('#countdown');
  var countdownContainer = document.querySelector('#countdownContainer');
  countdown.style.fontSize = (2.5 + (countdownLoop%10)*0.8) + 'em';
  var tenth = 1-(((countdownLoop%10)+1)/10); // Numeral progress scaled 1.0 -> 0.1
  countdown.style.color = 'rgba(255, ' + 255*tenth + ', 0, ' + tenth + ')';
//  countdown.style.display = 'block';
  countdownContainer.style.display = 'flex';
  countdown.textContent = 3 - Math.floor(countdownLoop/10);
  countdownLoop++;
//console.log('countdownLoop: ' + countdownLoop);
  if (countdownLoop == 30) {
    clearInterval(countdownTimer);
    countdownContainer.style.display = 'none';
    document.querySelector('#word').style.display = 'block';
    document.querySelector('#timeLeft').style.display = 'block';
    document.querySelector('#timeDisplay').style.display = 'block';
    document.querySelector('#scoreBoard').style.display = 'block';
    countdownLoop = 0;
    // Start the game
    if (level == 0) {
      gameStart();
    }
    // Start the next round
    else {
      rt = setInterval(updateTimeBar, 100); // Restart in-game timer
      getNextWord();
    }
  }
}
function gameStart() {
  document.querySelector('#word').style.display = 'block';
  document.querySelector('#timeLeft').style.display = 'block';
  document.querySelector('#timeDisplay').style.display = 'block';
  document.querySelector('#scoreBoard').style.display = 'block';
  
  // Enable detection of keyboard events
  document.addEventListener('keydown', processKeyPress);
  wordNumber = 0;
  //level = 0;
  timePerLetter = d[difficulty];
  timeAllowed = 5000;
  startTime = Date.now(); // Relative time snapshot in milliseconds
  score = 0;
  rt = setInterval(updateTimeBar, 100); // Set timer running
  getNextWord(); // Load the first word
}

// Update the time bar (indicator) and detect out of time game end
//
function updateTimeBar() {
console.log(startTime);
//var milliseconds = Date.now() - startTime;
  timeLeft = startTime - Date.now() + timeAllowed; // milliseconds
//console.log(timeLeft);
  let indicatorSize = timeLeft / 200;
  if (indicatorSize > 100) {indicatorSize = 100};
  document.querySelector('#timeIndicator').style.width = indicatorSize + '%';
  updateScore();
  document.querySelector('#timeDisplay').textContent = (timeLeft/1000).toFixed(1) + 's';
  if (timeLeft < 0) {
    gameEnd();
    console.log('out of time!');
  }
}
// Update score display
//
function updateScore() {
  let scoreStr = "0000" + score;
  document.querySelector('#scoreBoard').textContent = scoreStr.slice(scoreStr.length - 5, scoreStr.length);
}

// Looks for the next word to display
// Handles moving on to the next level and completion of the game
// 
function getNextWord() {
  if (wordNumber < wordList[level].length) {
    nextWord = wordList[level][wordNumber];
    // load next word
    word = document.querySelector('#word');
    word.textContent = nextWord;
    // Increase time remaining according to word length
    timeAllowed += nextWord.length * 1000 * d[difficulty];
    wordNumber++;
  }
  // Next level
  else {
    //console.log('Next level!');
    clearInterval(rt); // Stop counting time down
    let timeBonus = Math.floor(timeLeft * 15 * (difficulty + 1) / timePerLetter / 5000) * 5;
    console.log('Time bonus:' + timeBonus);
    score += timeBonus;
    startTime = Date.now();
    timeAllowed = 5000;
    level++;
    wordNumber = 0;
    if (level < wordList.length) {
      document.querySelector('#prompt').textContent = 'Round ' + (level+1) + ': ' + roundName[level];
      countdown();
      //getNextWord();
    }
    else {
      gameEnd();
      console.log('end of levels!');
    }
  }
}

// Update display features at game end
//
function gameEnd() {
  clearInterval(rt);
  document.removeEventListener('keydown', processKeyPress);
  document.querySelector('#scoreBoard').style.display = 'none';
  document.querySelector('#timeDisplay').style.display = 'none';
  document.querySelector('#timeLeft').style.display = 'none';
  document.querySelector('#word').style.display = 'none';
  let egd = document.querySelector('#endGameDisplay');
  // Test for game completed or out of time
  if (timeLeft < 0) {
    egd.textContent = 'Out of Time!!!';    
  }
  else {
    egd.textContent = 'Game Completed';
  }
  egd.textContent += '! You Scored ' + score + ' Points.';
  egd.style.display = 'block';
  document.querySelector('#prompt').textContent = 'Type the words coming at you!';
  document.querySelector('#go').style.display = 'block';
  document.querySelector('#d' + difficulty).focus();
  console.log('gameEnd!');
}

// Handle a keystroke, comparing it to the character
// next required, and process accordingly
//
function processKeyPress(event) {
  word = document.querySelector('#word');
  // Check keystroke matches the next letter of the word displayed
  if (event.key == word.textContent[0]) {
    // Remove the first letter of the word
    word.textContent = word.textContent.slice(1, word.textContent.length);
    document.body.style.background = 'linear-gradient(to left top, green, black) fixed';
    setTimeout(resetBackground, 200);
    score += 10;
    // Move on to next word when this one is done
    if (word.textContent == '') {
      score += nextWord.length * 25;
      getNextWord();
    }
  }
  else if (event.key != "Shift" &&
            event.key != "Control" &&
            event.key != "Alt" &&
            event.key != "ContextMenu" &&
            event.key != "CapsLock" &&
            event.key != "NumLock" &&
            event.key != "F5") {
    document.body.style.background = 'linear-gradient(to left top, red, black) fixed';
    setTimeout(resetBackground, 200);
  }
}

// Adds the abilty to navigate difficulty level buttons using the arrow keys
//
function addArrowKeys() {
  document.querySelector('#d0').addEventListener('keydown', function(event) {
    if (event.code == 'ArrowRight') {
      document.querySelector('#d1').focus();
    }
  } );
  document.querySelector('#d1').addEventListener('keydown', function(event) {
    if (event.code == 'ArrowRight') {
      document.querySelector('#d2').focus();
    } else if (event.code == 'ArrowLeft') {
      document.querySelector('#d0').focus();
    }
  } );
  document.querySelector('#d2').addEventListener('keydown', function(event) {
    if (event.code == 'ArrowLeft') {
      document.querySelector('#d1').focus();
    }
  } );
}

// Reset background colours
//
function resetBackground () {
  document.body.style.background = 'linear-gradient(to left top, navy, black) fixed';
}
