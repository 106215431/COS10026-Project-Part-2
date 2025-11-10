<?php include 'header.inc'; ?>
<?php include 'nav.inc'; ?>

<main>
    <div class="job-layout">
        <div class="job-list">
            <h1 id="h1">Job Listings</h1>

            <?php
            require_once "settings.php";
            $conn = mysqli_connect($host, $user, $pwd, $sql_db);

            if (!$conn) {
                echo "<p style='color:red;'>Database connection failed: " . mysqli_connect_error() . "</p>";
            } else {
                $query = "SELECT * FROM jobs";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<article class='job-card-listing' id='" . htmlspecialchars(strtolower(str_replace(' ', '-', $row['job_title']))) . "'>";
                        echo "<div>";
                        echo "<h2>" . htmlspecialchars($row['job_title']) . "</h2>";
                        echo "<p><strong>Reference Number:</strong> " . htmlspecialchars($row['reference_number']) . "</p>";
                        echo "<p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
                        echo "<p><strong>Salary:</strong> " . htmlspecialchars($row['salary_range']) . "</p>";
                        echo "<p><strong>Reports to:</strong> " . htmlspecialchars($row['reports_to']) . "</p>";

                        // Break the description into paragraphs and lists if possible
                        $desc = nl2br(htmlspecialchars($row['description']));
                        echo "<p>" . $desc . "</p>";

                        echo "<a href='apply.php' class='apply-button'>Apply Now</a>";
                        echo "</div>";

                        // Checks if the image_url column exists and isnt empty to use as the image, otherwise use placeholder
                        $img = isset($row['image_url']) && $row['image_url'] != "" 
                              ? $row['image_url'] 
                              : 'https://106215431.github.io/COS10026-Project-Part-2/images/job-placeholder.jpg';
                        echo "<img src='" . htmlspecialchars($img) . "' alt='" . htmlspecialchars($row['job_title']) . "' class='job-icon-listing'>";
                        echo "</article>";
                    }
                } else {
                    echo "<p>No job listings available at the moment.</p>";
                }

                mysqli_close($conn);
            }
            ?>
        </div>
        <aside>
            <h3>Other Open Positions:</h3>
            <ol>
                <li>Pentester</li>
                <li>UI Designer</li>
                <li>UX Designer</li>
            </ol>
        </aside>
    </div>
</main>

<?php include 'footer.inc'; ?>
