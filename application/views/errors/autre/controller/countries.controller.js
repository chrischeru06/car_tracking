const countriesModel = require('../model/countries.model')

const getAll = async (req, res) => {
          try {
                    const countries = await countriesModel.getAll()
                    res.status(200).json(countries)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

module.exports = {
          getAll
}