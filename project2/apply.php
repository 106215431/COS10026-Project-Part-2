<?php include 'header.inc'; ?>

   <?php include 'nav.inc'; ?>
  <section>
  <h1 class="formname">Job Application Form</h1>
  <form action='process_eoi.php' method="post" novalidate = 'novalidate'>
    <!-- Job Reference -->
    <label for="jobRef">Job Reference Number:</label>
    <select id="jobRef" name="jobRef" required>
      <option value="" disabled selected>Select Job</option>
      <option value="CE-VIC-1025">CE-VIC-1025</option>
      <option value="CTO-VIC-78">CTO-VIC-78</option>
      <option value="PE-QLD-1243">PE-QLD-1243</option>
      <option value="CA-ACT-2231">CA-ACT-2231</option>
      <option value="CC-TAS-773">CC-TAS-773</option>
    </select>
    <!-- Personal Info -->
    <label for="firstName">First Name:</label>
    <input type="text" id="firstName" name="firstName" maxlength="20"
           pattern="[A-Za-z]{1,20}" required>

    <label for="lastName">Last Name:</label>
    <input type="text" id="lastName" name="lastName" maxlength="20"
           pattern="[A-Za-z]{1,20}" required>
    <!-- 
    Portions of this Regex for Date of Birth were generated with assistance from 
    ChatGPT (GPT-4).
    Prompt: Can you help me create a regex to validate date of birth in dd/mm/yyyy format?
    Reviewed, modified, and approved by Vu Gia Hien.
    -->
    <!-- Regex for proper dob validation -->
    <label for="dob">Date of Birth (dd/mm/yyyy):</label>
    <input type="text" id="dob" name="dob" placeholder="dd/mm/yyyy"
           pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/[0-9]{4}$" required>
    
    <!-- Gender -->
    <fieldset>
      <legend>Gender</legend>
      <label><input type="radio" name="gender" value="Male" required> Male</label>
      <label><input type="radio" name="gender" value="Female"> Female</label>
      <label><input type="radio" name="gender" value="Other"> Other</label>
    </fieldset>

    <!-- Address -->
    <label for="address">Street Address:</label>
    <input type="text" id="address" name="address" maxlength="40" required>

    <label for="suburb">Suburb/Town:</label>
    <input type="text" id="suburb" name="suburb" maxlength="40" required>

    <label for="state">State:</label>
    <select id="state" name="state" required>
      <option value="" disabled selected>Select State</option>
      <option value="VIC">VIC</option>
      <option value="NSW">NSW</option>
      <option value="QLD">QLD</option>
      <option value="NT">NT</option>
      <option value="WA">WA</option>
      <option value="SA">SA</option>
      <option value="TAS">TAS</option>
      <option value="ACT">ACT</option>
    </select>

    <label for="postcode">Postcode:</label>
    <input type="text" id="postcode" name="postcode" pattern="^\d{4}$" maxlength="4" required>

    <!-- Email Address -->
    <!-- Email format is xxx@x.com or .org/.vn/.edu -->
    <!-- 
    Portions of this RegEx for Email Address were generated with assistance from 
    the Regex Generator (URL:https://regex-generator.olafneumann.org/).
    Reviewed, modified, and approved by Vu Gia Hien.
    -->
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email"
           pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$" required>

    <!-- 8-12 digits, numbers only-->
    <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone" pattern="^[0-9 ]{8,12}$" maxlength="12" minlength="8" required>

    <!-- Technical Skills -->
    <!-- Networking is checked by default -->
    <fieldset>
      <legend>Technical Skills</legend>
      <div class="checkbox-group">
        <label><input type="checkbox" name="skills[]" value="Networking" checked> Networking</label>
        <label><input type="checkbox" name="skills[]" value="Cybersecurity"> Cybersecurity</label>
        <label><input type="checkbox" name="skills[]" value="Programming"> Programming</label>
        <label><input type="checkbox" name="skills[]" value="Database"> Database</label>
      </div>
    </fieldset>

    <!-- Other Skills -->
    <label for="otherSkills">Other Skills:</label>
    <textarea id="otherSkills" name="otherSkills" maxlength="200"></textarea>

    <div class="submit-btn">
      <button type="submit">Apply</button>
    </div>
  </form>
  </section>
   <?php include 'footer.inc'; ?>


