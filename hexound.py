from flask import Response, render_template, request, Flask, send_file, jsonify
import json
import sqlite3
from flask_cors import CORS
from pprint import pprint

app = Flask(__name__, static_folder='mods')
CORS(app)

def isset(i):
    try:
      return isinstance(i, type(i))
    except:
      return False

@app.route("/mods")
def mods():
    mods = None
    with open('mods.json') as f:
        mods = json.load(f)
    for mod in mods:
        try:
          isinstance(mod['time'], str)
        except:
          mod['time'] = '1522011600'
    return jsonify(mods)

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        return '213'

@app.route("/usr", methods = ['POST'])
def usr_reg():
    if request.method == 'POST':
        data = request.form
        if isset(data['login']) and isset(data['password']):
            return 'Reg is possible.'
    else:
        return 'GTFO'

if __name__ == "__main__":
    app.run(host='0.0.0.0')
