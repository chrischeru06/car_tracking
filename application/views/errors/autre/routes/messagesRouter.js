const express = require('express')
const messagesController = require('../controller/messages.controller')

const messagesRouter = express.Router()

messagesRouter.get('/:commandeId', messagesController.getAll)
messagesRouter.post('/:commandeId', messagesController.createMessage)

module.exports = messagesRouter