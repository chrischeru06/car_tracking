const { query } = require("../functions/db")
const getAll = async () => {
          try {
                    var sqlQuery = "SELECT * FROM syst_provinces ORDER BY PROVINCE_NAME "
                    return await query(sqlQuery)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

module.exports ={
          getAll
}