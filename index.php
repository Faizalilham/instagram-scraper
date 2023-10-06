<!DOCTYPE html>
<html lang="en">
<head>
    <title>Instagram Profile Post Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333; /* Ubah warna judul */
        }

        form {
            text-align: center;
            max-width: 400px; /* Batasi lebar form */
            margin: 0 auto; /* Pusatkan form di tengah halaman */
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold; /* Beri teks label ketebalan */
            color: #555; /* Ubah warna teks label */
        }

        input[type="text"] {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px; /* Tambahkan jarak antara input dan tombol */
        }

        button[type="button"] {
            background-color: #000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s; /* Tambahkan efek hover */
        }

        button[type="button"]:hover {
            background-color: #41d11c;
        }

        #profile-data {
            margin-top: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan ke tabel */
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>

</head>
<body>
    <h1>Instagram Profile Posts Data</h1>
    <form>
        <label for="username">Enter Instagram Username:</label>
        <input type="text" id="username" name="username" required>
        <button type="button" id="fetch-button">Fetch Data</button>
        <button type="button" id="download-button">Download CSV</button>
    </form>

    <!-- Display the fetched data here -->
    <div id="profile-data">
        <!-- Table to display the fetched data -->
        <table id="post-table">
            <thead>
                <tr>
                    <th>Post Date</th>
                    <th>Caption</th>
                    <th>Link</th>
                    <th>Likes</th>
                    <th>Comments</th>
                    <th>Followers</th>
                    <th>Engagement</th>
                    <th>Sentimen</th>
                </tr>
            </thead>
            <tbody>
                <!-- Posts will be added here dynamically -->
            </tbody>
        </table>
    </div>

    <script>
        // Function to make an AJAX request and update the table
        function fetchProfileData() {
            const username = document.getElementById('username').value;
            const url = `http://127.0.0.1:5000/get-instagram-profile?username=${username}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Create a table row for each post
                    const tableBody = document.querySelector('#post-table tbody');
                    tableBody.innerHTML = '';

                    data.forEach(post => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${post.date}</td>
                            <td>${post.caption}</td>
                            <td>${post.link}</td>
                            <td>${post.likes}</td>
                            <td>${post.comments}</td>
                            <td>${post.followers}</td>
                            <td>${post.engagement}</td>
                            <td>${post.sentimen}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        // Function to generate and trigger the CSV download
        function downloadCSV(data) {
            const csv = 'Post Date,Caption,Likes,Comments,Engagement\n' + data.map(post => (
                `${post.date},"${post.caption}",${post.likes},${post.comments},${post.engagement}`
            )).join('\n');

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'profile_data.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Add click event listener to the "Fetch Data" button
        document.getElementById('fetch-button').addEventListener('click', fetchProfileData);

        // Add click event listener to the "Download CSV" button
        document.getElementById('download-button').addEventListener('click', () => {
            fetch(`/get-instagram-profile?username=${document.getElementById('username').value}`)
                .then(response => response.json())
                .then(data => downloadCSV(data))
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    </script>
</body>
</html>
