const { query } = require("../functions/db")
const getAll = async (communeId) => {
          try {
                    var sqlQuery = "SELECT * FROM syst_zones WHERE COMMUNE_ID = ? ORDER BY ZONE_NAME"
                    return await query(sqlQuery, [communeId])
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

module.exports ={
          getAll
}