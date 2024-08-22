<template>
  <div class="greetings">
    <h1 class="green">{{ msg }}</h1>
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="d-flex flex-column">
          <label class="form-label">CEP Inicial</label>
          <input name="dsCep" label="CEP" type="text" v-model="req.cep1" @input="checkCepLength(1)" class="form-control"
            placeholder="Digite o CEP sem -" maxlength="8" />
        </div>
      </div>
      <div class="col-6">
        <div class="d-flex flex-column">
          <label class="form-label">CEP Final</label>
          <input name="dsCep" label="CEP" type="text" v-model="req.cep2" class="form-control" @input="checkCepLength(2)"
            placeholder="Digite o CEP sem -" maxlength="8" />
        </div>
      </div>
      <div class="d-flex mt-2">
        <button :disabled="!isButtonEnabled" type="button" class="btn btn-success"
          @click="calculateDistance">Calcular</button>
      </div>
    </div>

    <div class="mt-3 upload-section">
      <div class="input-group justify-content-center">
        <label class="inputLabel" for='selecao-arquivo'>Importar CSV</label>
        <input id='selecao-arquivo' ref='selecao-arquivo' type="file" class="form-control" accept=".csv"
          @change="handleFileUpload">
        <button type="submit" class="btn btn-success" @click="uploadFile"
          :disabled="!isEnableUpload"><span>Upload</span></button>
      </div>
      <div v-if="uploading" class="progress mt-3">
        <div class="progress-bar" role="progressbar" :style="{ width: `${progress}%` }" :aria-valuenow="progress"
          aria-valuemin="0" aria-valuemax="100">
          {{ progress }}%
        </div>
      </div>
    </div>

    <div class="mt-4" v-show="showImportação">
      <h3 class="d-flex justify-content-center">Importação</h3>

      <div class="grid-container">
        <div class="grid-item" v-for="(item, index) in csvData" :key="index">
          <div>
            <strong>Linha:</strong> {{ (index + 1) }}
          </div>
          <div>
            <strong>CEP Inicial:</strong> {{ (item.origem ? item.origem.cep : 'N/A') }}
          </div>
          <div>
            <strong>CEP Final:</strong> {{ (item.destino ? item.destino.cep : 'N/A') }}
          </div>
          <div v-if="item.success">
            <strong>Distância Calculada:</strong> {{ formatDistance(item.distance) }} km
          </div>
          <div v-if="item.success">
            <strong>Hora da Consulta:</strong> {{ formatDate(item.createdAt) }} às {{ formatTime(item.createdAt) }}
          </div>
          <div>
            <strong>Status:</strong>
            <span class="greenStatus" v-if="item.success">&nbsp;OK</span>
            <span class="redStatus" v-else>&nbsp;{{ item.error }}</span>
          </div>
        </div>

      </div>
    </div>

    <div class="mt-4">
      <h3 class="d-flex justify-content-center">Histórico</h3>
      <div class="grid-container">
        <div class="grid-item" v-for="(item, index) in distances" :key="index">
          <div>
            <strong>CEP Inicial:</strong> {{ item.cepInicial }}
          </div>
          <div>
            <strong>CEP Final:</strong> {{ item.cepFinal }}
          </div>
          <div>
            <strong>Distância Calculada:</strong> {{ formatDistance(item.distancia) }} km
          </div>
          <div>
            <strong>Hora da Consulta:</strong> {{ formatDate(item.horaConsulta) }} às {{
              formatTime(item.horaConsulta)
            }}
          </div>
        </div>
      </div>
    </div>
  </div>


</template>

<script>
import axios from 'axios';
import { useToast } from "vue-toastification";

export default {
  setup() {
    const toast = useToast();
    return { toast }
  },

  name: 'Main',
  props: {
    msg: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      req: {
        cep1: '',
        cep2: ''
      },
      address1: {
        cep: ''
      },
      address2: {
        cep: ''
      },
      csvData: {},
      showImportação: false,
      enableUpload: false,
      isCep1Ready: false,
      isCep2Ready: false,
      distances: [],
      errorMessage: '',
      uploading: false,
      progress: 0,
      fileToUp: ''
    }
  },
  computed: {
    isButtonEnabled() {
      return this.isCep1Ready && this.isCep2Ready;
    },
    isEnableUpload() {
      return this.enableUpload
    }
  },
  mounted() {
    this.getBanco()

  },

  methods: {
    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.enableUpload = true
      }
    },
    uploadFile(event) {
      const file = this.$refs['selecao-arquivo'].files[0];
      this.enableUpload = true

      if (file && file.type === 'text/csv') {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.parseCSV(e.target.result);
        };
        reader.readAsText(file);

      } else {
        this.errorMessage = 'Por favor, selecione um arquivo CSV.';
      }
    },
    async parseCSV(csvText) {
      let txt = csvText.replaceAll('\r', '');
      txt = txt.replaceAll(' ', '');
      const lines = txt.split('\n');
      let result = [];
      const headers = lines.shift().split(';'); // Assumindo que a primeira linha contém os cabeçalhos

      for (let line of lines) {

        const currentLine = line.split(';');

        let vl = {};

        for (let i = 0; i < currentLine.length; i++) {
          const v = currentLine[i];
          vl[headers[i]] = await this.findCep(v.trim())
        }

        result.push(await this.processLine(vl));

      }

      this.csvData = result;
      this.errorMessage = '';
      this.getBanco();
    },
    async processLine(line) {
      if (!line.origem || !line.destino) {
        line.success = false;
        line.error = 'CEPs de origem ou destino inválidos';
        return line
      }

      let distance = await this.calculateDistanceAPI(line.origem, line.destino);
      this.showImportação = true;

      return {
        ...line,
        ...distance,
        success: true,
      }
    },
    formatDistance(distance) {
      return parseFloat(distance).toFixed(3);
    },
    formatDate(dateTimeString) {
      const [date,] = dateTimeString.split(' ');
      const [year, month, day] = date.split('-');
      return `${day}/${month}/${year}`;
    },
    formatTime(dateTimeString) {
      const [, time] = dateTimeString.split(' ');
      return time;
    },
    async findCep(cep) {
      if (!cep) {
        return;
      }
      try {
        let { data } = await axios.get(`http://localhost:8080/cep/${cep}`);

        return data.cep;

      } catch (error) {
        return;
      }
    },
    async fetchAddress(cepNumber) {
      let cep = cepNumber === 1 ? this.req.cep1 : this.req.cep2;

      if (cep && cep.length === 8) {
        try {
          let response = await axios.get(`http://localhost:8080/cep/${cep}`);

          let address = {
            cep: response.data.cep,
          };

          if (cepNumber === 1) {
            this.address1 = address;
            this.isCep1Ready = true;

          } else {
            this.address2 = address;
            this.isCep2Ready = true;

          }

        } catch (error) {
          this.toast.error(`CEP ${cep} não encontrado`, {
            timeout: 2000,
            position: "top-center",
          });
        }
      }
    },
    checkCepLength(cepNumber) {
      let cep = cepNumber === 1 ? this.req.cep1 : this.req.cep2;
      if (cep.length === 8) {
        this.fetchAddress(cepNumber);
      } else {
        if (cepNumber === 1) {
          this.isCep1Ready = false;
        } else {
          this.isCep2Ready = false;
        }
      }
    },

    async calculateDistanceAPI(origem, destino) {
      try {
        let { data } = await axios.post('http://localhost:8080/distance', {
          origem,
          destino
        });

        return data;

      } catch (error) {
        return {
          success: false,
          error: "Erro ao calcular a distância"
        };
      }
    },

    async calculateDistance() {
      try {

        await this.calculateDistanceAPI(this.address1.cep, this.address2.cep);

        this.getBanco();

      } catch (error) {
        console.error("Erro ao calcular a distância:", error);
      }
    },

    async getBanco() {
      try {
        let { data } = await axios.get('http://localhost:8080/distance');

        this.distances = data.map((dist) => {
          return {
            cepInicial: dist.origem.cep,
            cepFinal: dist.destino.cep,
            distancia: dist.distance,
            horaConsulta: dist.createdAt
          };
        });

      } catch (error) {
        console.error(error);
      }
    }
  }
}

</script>

<style scoped>
.upload-section {
  padding: 20px;
}

.error-message {
  color: red;
  margin-top: 10px;
}

.progress {
  height: 30px;
  background-color: #f3f3f3;
}

.progress-bar {
  height: 100%;
  background-color: #28a745;
  transition: width 0.4s ease;
}

.greenStatus {
  font-weight: bold;
  color: rgb(14, 218, 14);
}

.redStatus {
  color: red;
}

.grid-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
  padding: 20px;
}

.grid-item {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 15px;
  background-color: #f9f9f9;
}

input[type='file'] {
  display: none;
}

.btn.btn-success {
  margin-top: 10px;
  margin-bottom: 3px;
}

.inputLabel {
  background-color: #3498db;
  border-radius: 5px;
  color: #fff;
  cursor: pointer;
  margin: 10px;
  padding: 6px 20px;
}

.form {
  max-width: 94%;
  padding-left: 10px;
}

.greetings h1,
.greetings h3 {
  text-align: left;
  padding-left: 0;
}

label {
  margin-bottom: .2rem !important;
}
</style>
