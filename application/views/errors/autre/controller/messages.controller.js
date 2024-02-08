const messagesModel = require('../model/messages.model')

const getAll = async (req, res) => {
          try {
                    const { commandeId } = req.params
                    const { offset, limit } = req.query
                    const messages = await messagesModel.getAll(commandeId, offset, limit)
                    res.status(200).json(messages)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

const createMessage = async (req, res) => {
          try {
                    const { commandeId } = req.params
                    const { ID_RESTO, CONTENU, ID_STATUS_COMMANDE } = req.body
                    const { insertId } = await messagesModel.createMessage(commandeId, ID_RESTO, CONTENU, ID_STATUS_COMMANDE)
                    res.status(200).json({ ID_RESTO, CONTENU, ID_STATUS_COMMANDE, ID_COMMANDE: commandeId, ID_COMMANDE_MESSAGE: insertId })
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

module.exports = {
          getAll,
          createMessage
}