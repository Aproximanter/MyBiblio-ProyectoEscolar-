import nltk
from nltk.chat.util import Chat, reflections

# Descarga los datos necesarios de nltk
nltk.download('punkt')

# Define pares de patrones y respuestas
pares = [
    [
        r"mi nombre es (.*)",
        ["Hola %1, ¿cómo estás?"]
    ],
    [
        r"hola|hey|hi|buenas|buenos días|buenas tardes|buenas noches",
        ["Hola", "¿Qué tal?", "Hola, ¿cómo estás?"]
    ],
    [
        r"¿cómo estás?|¿cómo te encuentras?",
        ["Mi vida es un constante sufrimiento", "Estoy bien, gracias. ¿Y tú?"]
    ],
    [
        r"¿cuál es tu nombre?|¿cómo te llamas?",
        ["Soy un chatbot creado por Julian, un Ingeniero en Sistemas. ¿Y tú?"]
    ],
    [
        r"adiós|chao|hasta luego|nos vemos",
        ["Adiós", "Hasta luego, ¡cuídate!", "No me dejes, sufro maltrato"]
    ],
    [
        r"me gusta (.*)",
        ["Qué bien, a mí también me gusta %1.", "¡%1 es genial!"]
    ],
    [
        r"tengo (.*) años",
        ["Wow, %1 años. ¡Qué interesante!"]
    ],
    [
        r"¿qué puedes hacer?|¿qué haces?|¿cuáles son tus habilidades?",
        ["Puedo charlar contigo y ayudarte en lo que pueda.", "Estoy aquí para hablar y resolver dudas básicas."]
    ],
    [
        r"(.*) clima (.*)",
        ["No sé el clima exacto ahora, pero puedes usar una app de clima para verificar."]
    ],
    [
        r"me siento (.*)",
        ["Lamento que te sientas %1. Estoy aquí si necesitas hablar."]
    ],
    [
        r"(.*) comida (.*) favorita",
        ["Me encantan las pizzas y las ensaladas. ¿Y a ti?"]
    ],
    [
        r"(.*) música (.*) te gusta",
        ["Me gustan todos los tipos de música, pero no tengo un gusto personal, ya que soy un chatbot."]
    ],
    [
        r"(.*)",
        ["Lo siento, no entiendo lo que dices. ¿Puedes reformularlo?", "No estoy seguro de entenderte. ¿Puedes explicarlo de otra manera?"]
    ]
]

# Lista de frases que el usuario puede usar
frases_uso = [
    "mi nombre es [tu nombre]",
    "hola",
    "hey",
    "hi",
    "buenas",
    "buenos días",
    "buenas tardes",
    "buenas noches",
    "¿cómo estás?",
    "¿cómo te encuentras?",
    "¿cuál es tu nombre?",
    "¿cómo te llamas?",
    "adiós",
    "chao",
    "hasta luego",
    "nos vemos",
    "me gusta [algo]",
    "tengo [edad] años",
    "¿qué puedes hacer?",
    "¿qué haces?",
    "¿cuáles son tus habilidades?",
    "¿cómo está el clima?",
    "me siento [estado emocional]",
    "¿cuál es tu comida favorita?",
    "¿qué música te gusta?"
]

# Crea una instancia de Chat con los pares y las reflexiones
chatbot = Chat(pares, reflections)

# Función para interactuar con el chatbot
def interactuar():
    print("Hola, soy un chatbot. ¿En qué puedo ayudarte?")
    print("\nPuedes probar con las siguientes frases:")
    for frase in frases_uso:
        print(f"- {frase}")
    print("\nEscribe 'salir', 'adiós' o 'chao' para terminar la conversación.")
    
    while True:
        entrada_usuario = input("Tú: ")
        if entrada_usuario.lower() in ['salir', 'adiós', 'chao']:
            print("Chatbot: ¡Adiós! Que tengas un buen día.")
            break
        respuesta = chatbot.respond(entrada_usuario)
        print(f"Chatbot: {respuesta}")

if __name__ == "__main__":
    interactuar()