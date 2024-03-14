---
title: Shell Command Game
layout: page
permalink: /games/shell-command-game
---

  <h1>Shell Command Game</h1>
    <div id="commands">
        <p id="commandPrompt"></p>
        <input type="text" id="commandInput" onkeypress="handleKeyPress(event)">
        <button onclick="submitCommand()">Submit</button>
    </div>
    <p id="timer"></p>
    <pre id="result"></pre>

    <script>
        var alphabet = 'abcdefghijklmnopqrstuvwxyz'.split('');
        var shellCommands = [];
        var currentLetterIndex = 0;
        var startTime, endTime;
        var timerInterval;

        function startGame() {
            startTime = new Date();
            timerInterval = setInterval(updateTimer, 1000);
            nextCommand();
        }

        function updateTimer() {
            var now = new Date();
            var timeDiff = Math.floor((now - startTime) / 1000);
            document.getElementById('timer').textContent = "Time elapsed: " + timeDiff + " seconds";
        }

        function nextCommand() {
            if (currentLetterIndex >= alphabet.length) {
                submitCommands();
                return;
            }

            document.getElementById('commandPrompt').textContent = 
                "Enter a shell command that starts with the letter " + alphabet[currentLetterIndex] + ": ";

            document.getElementById('commandInput').value = '';
        }

        function submitCommand() {
            var command = document.getElementById('commandInput').value;
            shellCommands.push(command);

            currentLetterIndex++;
            nextCommand();
        }

        function submitCommands() {
            clearInterval(timerInterval);

            endTime = new Date();
            var timeDiff = Math.floor((endTime - startTime) / 1000);

            var result = "You entered the following commands:\n";
            for (var i = 0; i < shellCommands.length; i++) {
                result += alphabet[i] + ": " + shellCommands[i] + "\n";
            }

            result += "Total time taken: " + timeDiff + " seconds";
            document.getElementById('result').textContent = result;
        }

        function handleKeyPress(e) {
            if (e.keyCode === 13) { // Enter key
                submitCommand();
            }
        }

        window.onload = startGame;
    </script>
