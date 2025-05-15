<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KMSC</title>
    <link rel="icon" href="http://localhost/KMSC/favicon.ico ">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">
    <link href="../assets/css/output.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
    <?php include '../includes/header.php'; ?>
</head>
<body class="bg-gray-100">
  <main class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto p-6 space-y-6">

      <h2 class="text-4xl">BOOK ONLINE</h2>
      <p class="text-red-600 mb-4">Reserve Your Slot Here</p>

      <p class="mb-4">Select Date</p>
      <!-- Date Buttons -->
      <div id="dateButtons" class="flex flex-wrap mb-8"></div>

      <p class="mb-4">Select Time</p>
      <!-- Time Slots -->
      <div id="timeSlots" class="flex flex-wrap mb-8"></div>

      <p class="mb-4">Select Duration</p>
      <!-- Duration Selector -->
      <div class="flex items-center justify-between border border-gray-300 rounded-xl px-6 py-4 bg-white shadow-sm mb-8">
        <span class="text-lg font-semibold text-gray-700">Duration (mins)</span>
        <div class="flex items-center gap-4">
          <button id="decreaseBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-full text-lg font-bold">-</button>
          <span id="durationVal" class="font-bold text-xl text-gray-900">30</span>
          <button id="increaseBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-full text-lg font-bold">+</button>
        </div>
      </div>
    </div>
  </main>
  <?php include '../includes/footer.php'; ?>
  <script>
    const dateButtonsContainer = document.getElementById("dateButtons");
    const timeSlotsContainer = document.getElementById("timeSlots");

    function generateNextFiveDates() {
      const dates = [];
      const today = new Date();
      for (let i = 0; i < 5; i++) {
        const d = new Date(today);
        d.setDate(today.getDate() + i);
        dates.push({
          label: d.toLocaleDateString("en-US", { weekday: "short", month: "short", day: "numeric" }),
          value: d.toISOString().split("T")[0]
        });
      }
      return dates;
    }

    function generateTimeSlots(start = "08:00", end = "22:00", step = 30) {
      const [startHour, startMin] = start.split(":" ).map(Number);
      const [endHour, endMin] = end.split(":" ).map(Number);
      const slots = [];
      let current = new Date(0, 0, 0, startHour, startMin);
      const endTime = new Date(0, 0, 0, endHour, endMin);
      while (current <= endTime) {
        const hours = current.getHours().toString().padStart(2, "0");
        const minutes = current.getMinutes().toString().padStart(2, "0");
        slots.push(`${hours}:${minutes}`);
        current.setMinutes(current.getMinutes() + step);
      }
      return slots;
    }

    function filterTimeSlotsForDate(date, allSlots) {
      const now = new Date();
      const selected = new Date(date);
      if (
        selected.getFullYear() === now.getFullYear() &&
        selected.getMonth() === now.getMonth() &&
        selected.getDate() === now.getDate()
      ) {
        const currentTimeInMinutes = now.getHours() * 60 + now.getMinutes();
        return allSlots.filter(slot => {
          const [h, m] = slot.split(":" ).map(Number);
          return h * 60 + m > currentTimeInMinutes;
        });
      }
      return allSlots;
    }

    function renderDateButtons(dates) {
      dateButtonsContainer.innerHTML = "";
      dates.forEach((d, i) => {
      const btn = document.createElement("button");
      btn.textContent = d.label;
      btn.className =
        "bg-white border border-gray-300 transition px-4 py-2 rounded-lg text-sm shadow-sm hover:text-red-600";

      btn.onclick = () => {
        // Remove previous selection
        document.querySelectorAll("#dateButtons button").forEach(b => {
          b.classList.remove("text-red-600", "border-red-600");
          b.classList.add("bg-white", "border-gray-300");
        });

        // Select current slot
        btn.classList.remove("border-gray-300");
        btn.classList.add("text-red-600", "border-red-600");

        renderTimeSlots(d.value);
      };

      if (i === 0) btn.click(); // Auto-select today
      dateButtonsContainer.appendChild(btn);
      });
  }


    function renderTimeSlots(dateVal) {
      const allSlots = generateTimeSlots();
      const validSlots = filterTimeSlotsForDate(dateVal, allSlots);
      timeSlotsContainer.innerHTML = "";

      validSlots.forEach(slot => {
      const btn = document.createElement("button");
      btn.textContent = slot;
      btn.className =  "bg-white border border-gray-300 transition px-4 py-2 rounded-lg text-sm shadow-sm hover:text-red-600";

      btn.onclick = () => {
        // Unselect previous slot
        document.querySelectorAll("#timeSlots button").forEach(b => {
          b.classList.remove("text-red-600", "border-red-600");
          b.classList.add("bg-white", "border-gray-300");
        });

        // Select current slot
        btn.classList.remove("border-gray-300");
        btn.classList.add("text-red-600", "border-red-600");
      };

      timeSlotsContainer.appendChild(btn);
      });
    }


    renderDateButtons(generateNextFiveDates());

    const durationVal = document.getElementById("durationVal");
    const increaseBtn = document.getElementById("increaseBtn");
    const decreaseBtn = document.getElementById("decreaseBtn");
    let duration = 30;
    increaseBtn.onclick = () => {
      if (duration < 120) {
        duration += 30;
        durationVal.textContent = duration;
      }
    };
    decreaseBtn.onclick = () => {
      if (duration > 30) {
        duration -= 30;
        durationVal.textContent = duration;
      }
    };
  </script>
</body>
</html>