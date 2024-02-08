const collinesModel = require('../model/collines.model')

const getAll = async (req, res) => {
          try {
                    const { zoneId } = req.params
                    const collines = await collinesModel.getAll(zoneId)
                    res.status(200).json(collines)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

module.exports = {
          getAll
}