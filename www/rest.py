from flask import Flask, jsonify, request

app = Flask(__name__)


@app.route('/asjcjnasc', methods=['GET'])
def getData():

    return "test"
