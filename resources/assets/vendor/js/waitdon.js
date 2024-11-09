'use strict';
window.onload = function() {
  let countdown = 35;
  let score = 0;
  const countdownElement = document.getElementById("countdown");
  const downloadText = document.getElementById("downloadText");
  const progressBar = document.getElementById("progress-bar");
  const collectPointsMessage = document.getElementById("collectPointsMessage");
  const scoreElement = document.getElementById("score");
  const clickableDot = document.getElementById("clickableDot");
  const gameArea = document.getElementById("gameArea");

  const timer = setInterval(function() {
      countdown--;
      countdownElement.textContent = countdown;
      progressBar.style.width = ((35 - countdown) / 35 * 100) + '%';

      if (countdown <= 0) {
          clearInterval(timer);
          downloadText.style.display = "block";
          gameArea.style.display = "none";  // إخفاء اللعبة بعد انتهاء الوقت
          collectPointsMessage.textContent = `تهانينا! لقد جمعت ${score} نقطة.`;
      }
  }, 1000);

  // لعبة النقاط
  function moveDot() {
      const x = Math.random() * (gameArea.offsetWidth - clickableDot.offsetWidth);
      const y = Math.random() * (gameArea.offsetHeight - clickableDot.offsetHeight);
      clickableDot.style.left = `${x}px`;
      clickableDot.style.top = `${y}px`;
  }

  clickableDot.addEventListener('click', function() {
      score++;
      scoreElement.textContent = score;

      // تقليل العد التنازلي مع كل نقرة على النقطة
      if (countdown > 1) {
          countdown -= 1; // تقليل ثانية واحدة من العد
      }

      moveDot();
  });

  moveDot(); // حرك النقطة في البداية
};
