const mysql = require("mysql");
const util = require('util')

// Create a connection to the database

const connection = mysql.createConnection({
          host: 'localhost',
          user: 'usermbx',
          password: 'Mbx_burundi@!#20',
          database: 'wasili_eat',
          insecureAuth: true,
          port: 3306,
});

// open the MySQL connection
connection.connect((error) => {
          if (error) throw error;
          console.log("Successfully connected to the database: ");
});
const query = util.promisify(connection.query).bind(connection)

module.exports = {
          connection,
          query
};
