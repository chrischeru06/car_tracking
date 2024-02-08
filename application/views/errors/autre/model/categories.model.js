const { query } = require("../functions/db")

const getAll = async () => {
          try {
                    return await query('SELECT * FROM menu_categories')
          } catch (error) {
                    throw error
          }
}

module.exports = {
          getAll
}