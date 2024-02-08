const { query } = require("../functions/db")
const getAll = async () => {
          try {
                    var sqlQuery = "SELECT * FROM syst_countries ORDER BY CommonName "
                    return await query(sqlQuery)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

module.exports ={
          getAll
}