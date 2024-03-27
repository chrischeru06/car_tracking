<script>
	const mysql = require("mysql2");

// Create a connection to the database
const connection = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "chris",
});

function generateUniqueCode() {
  const timestamp = new Date().getTime().toString(16); // Utilisation du timestamp en base 16
  const randomNum = Math.floor(Math.random() * 1000); // Génération d'un nombre aléatoire entre 0 et 999
  const uniqueCode = timestamp + randomNum;

  return uniqueCode;
}

// Connect to the database
connection.connect();

// Function to update data
function updateData(id, codeunique) {
  return new Promise((resolve, reject) => {
    connection.query(
      "UPDATE tracking_data SET CODE_COURSE = ? WHERE id = ?",
      [codeunique, id],
      (error, results, fields) => {
        if (error) {
          reject(error);
        } else {
          resolve(results);
        }
      }
    );
  });
}

// Perform a query to select data
connection.query(
  "SELECT `id`,`ignition` FROM tracking_data WHERE `device_uid` = 4.4098856886e-314  ORDER BY `id` ASC",
  async (error, results, fields) => {
    if (error) {
      console.error("Error retrieving data: " + error.stack);
      return;
    }

    let course = 0;
    let valueurfinal = 0;
    // Process the retrieved data
    let codeunique = generateUniqueCode();

    for (let i = 0; i < results.length; i++) {
      if (results[i].ignition == 1 && valueurfinal == 0) {
        codeunique = generateUniqueCode();
        course++;
      }
      valueurfinal = results[i].ignition;

      try {
        await updateData(results[i].id, codeunique);
        console.log("Update successful for id: ", results[i].id);
      } catch (error) {
        console.error("Error updating data: " + error.stack);
      }
    }
    console.log(course);
    // Close the connection after all updates are done
    connection.end();
  }
);

</script>