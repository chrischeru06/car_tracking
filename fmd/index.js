const net = require('net');
const Parser = require('teltonika-parser-ex');
const binutils = require('binutils64');
const path = require('path');

const mysql = require("mysql");
const util = require("util");

// Create a connection to the database




let server = net.createServer((c) => {
    console.log("client connected");
    c.on('end', () => {
        console.log("client disconnected");
    });

    c.on('data', (data) => {

   
        let buffer = data;
        //console.log(buffer);
        let parser = new Parser(buffer);     

        if (parser.isImei) {
            c.write(Buffer.alloc(1, 1)); // send ACK for IMEI
        } else {


            let avl = parser.getAvl();
            var myJsonString = JSON.stringify(avl.records);
            //console.log(myJsonString)
            
            
            var donneGps = avl?.records?.map(({ gps, timestamp }) => {
                  return { gps, timestamp }
                }
              );

              if (donneGps) {

                var detail = donneGps[0].gps;
                console.log(donneGps[0].gps);

                console.log(JSON.stringify(avl))


              const connection = mysql.createConnection({
              host: "localhost",
              user: "Cratrcad",
              password: "QN:Y6Wtpa64e,6t3;/",
              database: "sbg_v2",
              });

              // open the MySQL connection
              connection.connect((error) => {
                      if (error) throw error;
                      console.log("Successfully connected to the database: ");
              });
              const query = util.promisify(connection.query).bind(connection);

              const detailsData = []

              detailsData.push([
                        detail.latitude,
                        detail.longitude,
                        detail.altitude,
                        detail.angle,
                        detail.satellites,
                        detail.speed,
                        JSON.stringify(myJsonString)
              ])
              query('INSERT INTO tracking_data(latitude, longitude,altitude,angle,satellites, vitesse,json) VALUES ?', [detailsData])
              }

          
            let writer = new binutils.BinaryWriter();
            writer.WriteInt32(avl.number_of_data);


            let response =writer.ByteBuffer;

           c.write(response); // send ACK for AVL DATA
           //console.log(test);

           c.write(Buffer.from('000000000000000F0C010500000007676574696E666F0100004312', 'hex'));
            
          //c.write("000000000000000F0C010500000007676574696E666F0100004312"); 
        }
 
    });

});

server.listen(2354, '161.97.118.14', () => {
    console.log("Server started ont 2354");
});