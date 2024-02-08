const { query } = require("../functions/db")
const getAll = async (provinceId) => {
          try {
                    var sqlQuery = "SELECT * FROM syst_communes WHERE PROVINCE_ID = ? ORDER BY COMMUNE_NAME"
                    return await query(sqlQuery, [provinceId])
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

module.exports ={
          getAll
}