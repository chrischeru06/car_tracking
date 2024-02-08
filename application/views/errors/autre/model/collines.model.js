const { query } = require("../functions/db")
const getAll = async (zoneId) => {
          try {
                    var sqlQuery = "SELECT * FROM syst_collines WHERE ZONE_ID = ? ORDER BY COLLINE_NAME"
                    return await query(sqlQuery, [zoneId])
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

module.exports ={
          getAll
}