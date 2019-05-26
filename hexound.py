from flask import Response, render_template, request, Flask, send_file, jsonify
import json
#import sqlite3
from flask_cors import CORS

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
    limit = request.args.get('limit', default = 20, type = int)
    offset = request.args.get('offset', default = 0, type = int)
    with open('mods.json') as f:
        mods = json.load(f)
    for mod in mods:
        try:
          isinstance(mod['time'], str)
        except:
          mod['time'] = '1522011600'
   #limit = len(mods) if limit > len(mods) else limit
   #offset = len(mods)-limit if offset > len(mods) else offset
    return jsonify(mods[offset:offset+limit])

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
