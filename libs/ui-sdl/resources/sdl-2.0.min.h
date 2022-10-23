typedef int8_t Sint8;
typedef uint8_t Uint8;
typedef int16_t Sint16;
typedef uint16_t Uint16;
typedef int32_t Sint32;
typedef uint32_t Uint32;
typedef int64_t Sint64;
typedef uint64_t Uint64;

typedef struct SDL_version {
    Uint8 major;
    Uint8 minor;
    Uint8 patch;
} SDL_version;

typedef struct SDL_Window SDL_Window;
typedef Sint32 SDL_JoystickID;
typedef Sint64 SDL_TouchID;
typedef Sint64 SDL_FingerID;
typedef Sint64 SDL_GestureID;
struct SDL_SysWMmsg;
typedef struct SDL_SysWMmsg SDL_SysWMmsg;

typedef enum
{
    SDL_SYSWM_UNKNOWN,
    SDL_SYSWM_WINDOWS,
    SDL_SYSWM_X11,
    SDL_SYSWM_DIRECTFB,
    SDL_SYSWM_COCOA,
    SDL_SYSWM_UIKIT,
    SDL_SYSWM_WAYLAND,
    SDL_SYSWM_MIR,
    SDL_SYSWM_WINRT,
    SDL_SYSWM_ANDROID,
    SDL_SYSWM_VIVANTE,
    SDL_SYSWM_OS2,
    SDL_SYSWM_HAIKU,
    SDL_SYSWM_KMSDRM,
    SDL_SYSWM_RISCOS
} SDL_SYSWM_TYPE;

struct SDL_SysWMinfo
{
    SDL_version version;
    SDL_SYSWM_TYPE subsystem;
    union
    {
        struct
        {
            struct HWND* window;
            struct HDC* hdc;
            struct HINSTANCE* hinstance;
        } win;
        struct
        {
            void *display;
            unsigned long window;
        } x11;
        struct
        {
            void *window;
        } cocoa;
        struct
        {
            struct wl_display *display;
            struct wl_surface *surface;
            void *shell_surface;
            struct wl_egl_window *egl_window;
            struct xdg_surface *xdg_surface;
            struct xdg_toplevel *xdg_toplevel;
            struct xdg_popup *xdg_popup;
            struct xdg_positioner *xdg_positioner;
        } wl;
        Uint8 dummy[64];
    } info;
};

typedef struct SDL_SysWMinfo SDL_SysWMinfo;

typedef struct SDL_CommonEvent {
  Uint32 type;
  Uint32 timestamp;
} SDL_CommonEvent;

typedef struct SDL_DisplayEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 display;
  Uint8 event;
  Uint8 padding1;
  Uint8 padding2;
  Uint8 padding3;
  Sint32 data1;
} SDL_DisplayEvent;

typedef struct SDL_WindowEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 windowID;
  Uint8 event;
  Uint8 padding1;
  Uint8 padding2;
  Uint8 padding3;
  Sint32 data1;
  Sint32 data2;
} SDL_WindowEvent;

typedef enum {
  SDL_SCANCODE_UNKNOWN = 0,
  SDL_SCANCODE_A = 4,
  SDL_SCANCODE_B = 5,
  SDL_SCANCODE_C = 6,
  SDL_SCANCODE_D = 7,
  SDL_SCANCODE_E = 8,
  SDL_SCANCODE_F = 9,
  SDL_SCANCODE_G = 10,
  SDL_SCANCODE_H = 11,
  SDL_SCANCODE_I = 12,
  SDL_SCANCODE_J = 13,
  SDL_SCANCODE_K = 14,
  SDL_SCANCODE_L = 15,
  SDL_SCANCODE_M = 16,
  SDL_SCANCODE_N = 17,
  SDL_SCANCODE_O = 18,
  SDL_SCANCODE_P = 19,
  SDL_SCANCODE_Q = 20,
  SDL_SCANCODE_R = 21,
  SDL_SCANCODE_S = 22,
  SDL_SCANCODE_T = 23,
  SDL_SCANCODE_U = 24,
  SDL_SCANCODE_V = 25,
  SDL_SCANCODE_W = 26,
  SDL_SCANCODE_X = 27,
  SDL_SCANCODE_Y = 28,
  SDL_SCANCODE_Z = 29,
  SDL_SCANCODE_1 = 30,
  SDL_SCANCODE_2 = 31,
  SDL_SCANCODE_3 = 32,
  SDL_SCANCODE_4 = 33,
  SDL_SCANCODE_5 = 34,
  SDL_SCANCODE_6 = 35,
  SDL_SCANCODE_7 = 36,
  SDL_SCANCODE_8 = 37,
  SDL_SCANCODE_9 = 38,
  SDL_SCANCODE_0 = 39,
  SDL_SCANCODE_RETURN = 40,
  SDL_SCANCODE_ESCAPE = 41,
  SDL_SCANCODE_BACKSPACE = 42,
  SDL_SCANCODE_TAB = 43,
  SDL_SCANCODE_SPACE = 44,
  SDL_SCANCODE_MINUS = 45,
  SDL_SCANCODE_EQUALS = 46,
  SDL_SCANCODE_LEFTBRACKET = 47,
  SDL_SCANCODE_RIGHTBRACKET = 48,
  SDL_SCANCODE_BACKSLASH = 49,
  SDL_SCANCODE_NONUSHASH = 50,
  SDL_SCANCODE_SEMICOLON = 51,
  SDL_SCANCODE_APOSTROPHE = 52,
  SDL_SCANCODE_GRAVE = 53,
  SDL_SCANCODE_COMMA = 54,
  SDL_SCANCODE_PERIOD = 55,
  SDL_SCANCODE_SLASH = 56,
  SDL_SCANCODE_CAPSLOCK = 57,
  SDL_SCANCODE_F1 = 58,
  SDL_SCANCODE_F2 = 59,
  SDL_SCANCODE_F3 = 60,
  SDL_SCANCODE_F4 = 61,
  SDL_SCANCODE_F5 = 62,
  SDL_SCANCODE_F6 = 63,
  SDL_SCANCODE_F7 = 64,
  SDL_SCANCODE_F8 = 65,
  SDL_SCANCODE_F9 = 66,
  SDL_SCANCODE_F10 = 67,
  SDL_SCANCODE_F11 = 68,
  SDL_SCANCODE_F12 = 69,
  SDL_SCANCODE_PRINTSCREEN = 70,
  SDL_SCANCODE_SCROLLLOCK = 71,
  SDL_SCANCODE_PAUSE = 72,
  SDL_SCANCODE_INSERT = 73,
  SDL_SCANCODE_HOME = 74,
  SDL_SCANCODE_PAGEUP = 75,
  SDL_SCANCODE_DELETE = 76,
  SDL_SCANCODE_END = 77,
  SDL_SCANCODE_PAGEDOWN = 78,
  SDL_SCANCODE_RIGHT = 79,
  SDL_SCANCODE_LEFT = 80,
  SDL_SCANCODE_DOWN = 81,
  SDL_SCANCODE_UP = 82,
  SDL_SCANCODE_NUMLOCKCLEAR = 83,
  SDL_SCANCODE_KP_DIVIDE = 84,
  SDL_SCANCODE_KP_MULTIPLY = 85,
  SDL_SCANCODE_KP_MINUS = 86,
  SDL_SCANCODE_KP_PLUS = 87,
  SDL_SCANCODE_KP_ENTER = 88,
  SDL_SCANCODE_KP_1 = 89,
  SDL_SCANCODE_KP_2 = 90,
  SDL_SCANCODE_KP_3 = 91,
  SDL_SCANCODE_KP_4 = 92,
  SDL_SCANCODE_KP_5 = 93,
  SDL_SCANCODE_KP_6 = 94,
  SDL_SCANCODE_KP_7 = 95,
  SDL_SCANCODE_KP_8 = 96,
  SDL_SCANCODE_KP_9 = 97,
  SDL_SCANCODE_KP_0 = 98,
  SDL_SCANCODE_KP_PERIOD = 99,
  SDL_SCANCODE_NONUSBACKSLASH = 100,
  SDL_SCANCODE_APPLICATION = 101,
  SDL_SCANCODE_POWER = 102,
  SDL_SCANCODE_KP_EQUALS = 103,
  SDL_SCANCODE_F13 = 104,
  SDL_SCANCODE_F14 = 105,
  SDL_SCANCODE_F15 = 106,
  SDL_SCANCODE_F16 = 107,
  SDL_SCANCODE_F17 = 108,
  SDL_SCANCODE_F18 = 109,
  SDL_SCANCODE_F19 = 110,
  SDL_SCANCODE_F20 = 111,
  SDL_SCANCODE_F21 = 112,
  SDL_SCANCODE_F22 = 113,
  SDL_SCANCODE_F23 = 114,
  SDL_SCANCODE_F24 = 115,
  SDL_SCANCODE_EXECUTE = 116,
  SDL_SCANCODE_HELP = 117,
  SDL_SCANCODE_MENU = 118,
  SDL_SCANCODE_SELECT = 119,
  SDL_SCANCODE_STOP = 120,
  SDL_SCANCODE_AGAIN = 121,
  SDL_SCANCODE_UNDO = 122,
  SDL_SCANCODE_CUT = 123,
  SDL_SCANCODE_COPY = 124,
  SDL_SCANCODE_PASTE = 125,
  SDL_SCANCODE_FIND = 126,
  SDL_SCANCODE_MUTE = 127,
  SDL_SCANCODE_VOLUMEUP = 128,
  SDL_SCANCODE_VOLUMEDOWN = 129,
  SDL_SCANCODE_KP_COMMA = 133,
  SDL_SCANCODE_KP_EQUALSAS400 = 134,
  SDL_SCANCODE_INTERNATIONAL1 = 135,
  SDL_SCANCODE_INTERNATIONAL2 = 136,
  SDL_SCANCODE_INTERNATIONAL3 = 137,
  SDL_SCANCODE_INTERNATIONAL4 = 138,
  SDL_SCANCODE_INTERNATIONAL5 = 139,
  SDL_SCANCODE_INTERNATIONAL6 = 140,
  SDL_SCANCODE_INTERNATIONAL7 = 141,
  SDL_SCANCODE_INTERNATIONAL8 = 142,
  SDL_SCANCODE_INTERNATIONAL9 = 143,
  SDL_SCANCODE_LANG1 = 144,
  SDL_SCANCODE_LANG2 = 145,
  SDL_SCANCODE_LANG3 = 146,
  SDL_SCANCODE_LANG4 = 147,
  SDL_SCANCODE_LANG5 = 148,
  SDL_SCANCODE_LANG6 = 149,
  SDL_SCANCODE_LANG7 = 150,
  SDL_SCANCODE_LANG8 = 151,
  SDL_SCANCODE_LANG9 = 152,
  SDL_SCANCODE_ALTERASE = 153,
  SDL_SCANCODE_SYSREQ = 154,
  SDL_SCANCODE_CANCEL = 155,
  SDL_SCANCODE_CLEAR = 156,
  SDL_SCANCODE_PRIOR = 157,
  SDL_SCANCODE_RETURN2 = 158,
  SDL_SCANCODE_SEPARATOR = 159,
  SDL_SCANCODE_OUT = 160,
  SDL_SCANCODE_OPER = 161,
  SDL_SCANCODE_CLEARAGAIN = 162,
  SDL_SCANCODE_CRSEL = 163,
  SDL_SCANCODE_EXSEL = 164,
  SDL_SCANCODE_KP_00 = 176,
  SDL_SCANCODE_KP_000 = 177,
  SDL_SCANCODE_THOUSANDSSEPARATOR = 178,
  SDL_SCANCODE_DECIMALSEPARATOR = 179,
  SDL_SCANCODE_CURRENCYUNIT = 180,
  SDL_SCANCODE_CURRENCYSUBUNIT = 181,
  SDL_SCANCODE_KP_LEFTPAREN = 182,
  SDL_SCANCODE_KP_RIGHTPAREN = 183,
  SDL_SCANCODE_KP_LEFTBRACE = 184,
  SDL_SCANCODE_KP_RIGHTBRACE = 185,
  SDL_SCANCODE_KP_TAB = 186,
  SDL_SCANCODE_KP_BACKSPACE = 187,
  SDL_SCANCODE_KP_A = 188,
  SDL_SCANCODE_KP_B = 189,
  SDL_SCANCODE_KP_C = 190,
  SDL_SCANCODE_KP_D = 191,
  SDL_SCANCODE_KP_E = 192,
  SDL_SCANCODE_KP_F = 193,
  SDL_SCANCODE_KP_XOR = 194,
  SDL_SCANCODE_KP_POWER = 195,
  SDL_SCANCODE_KP_PERCENT = 196,
  SDL_SCANCODE_KP_LESS = 197,
  SDL_SCANCODE_KP_GREATER = 198,
  SDL_SCANCODE_KP_AMPERSAND = 199,
  SDL_SCANCODE_KP_DBLAMPERSAND = 200,
  SDL_SCANCODE_KP_VERTICALBAR = 201,
  SDL_SCANCODE_KP_DBLVERTICALBAR = 202,
  SDL_SCANCODE_KP_COLON = 203,
  SDL_SCANCODE_KP_HASH = 204,
  SDL_SCANCODE_KP_SPACE = 205,
  SDL_SCANCODE_KP_AT = 206,
  SDL_SCANCODE_KP_EXCLAM = 207,
  SDL_SCANCODE_KP_MEMSTORE = 208,
  SDL_SCANCODE_KP_MEMRECALL = 209,
  SDL_SCANCODE_KP_MEMCLEAR = 210,
  SDL_SCANCODE_KP_MEMADD = 211,
  SDL_SCANCODE_KP_MEMSUBTRACT = 212,
  SDL_SCANCODE_KP_MEMMULTIPLY = 213,
  SDL_SCANCODE_KP_MEMDIVIDE = 214,
  SDL_SCANCODE_KP_PLUSMINUS = 215,
  SDL_SCANCODE_KP_CLEAR = 216,
  SDL_SCANCODE_KP_CLEARENTRY = 217,
  SDL_SCANCODE_KP_BINARY = 218,
  SDL_SCANCODE_KP_OCTAL = 219,
  SDL_SCANCODE_KP_DECIMAL = 220,
  SDL_SCANCODE_KP_HEXADECIMAL = 221,
  SDL_SCANCODE_LCTRL = 224,
  SDL_SCANCODE_LSHIFT = 225,
  SDL_SCANCODE_LALT = 226,
  SDL_SCANCODE_LGUI = 227,
  SDL_SCANCODE_RCTRL = 228,
  SDL_SCANCODE_RSHIFT = 229,
  SDL_SCANCODE_RALT = 230,
  SDL_SCANCODE_RGUI = 231,
  SDL_SCANCODE_MODE = 257,
  SDL_SCANCODE_AUDIONEXT = 258,
  SDL_SCANCODE_AUDIOPREV = 259,
  SDL_SCANCODE_AUDIOSTOP = 260,
  SDL_SCANCODE_AUDIOPLAY = 261,
  SDL_SCANCODE_AUDIOMUTE = 262,
  SDL_SCANCODE_MEDIASELECT = 263,
  SDL_SCANCODE_WWW = 264,
  SDL_SCANCODE_MAIL = 265,
  SDL_SCANCODE_CALCULATOR = 266,
  SDL_SCANCODE_COMPUTER = 267,
  SDL_SCANCODE_AC_SEARCH = 268,
  SDL_SCANCODE_AC_HOME = 269,
  SDL_SCANCODE_AC_BACK = 270,
  SDL_SCANCODE_AC_FORWARD = 271,
  SDL_SCANCODE_AC_STOP = 272,
  SDL_SCANCODE_AC_REFRESH = 273,
  SDL_SCANCODE_AC_BOOKMARKS = 274,
  SDL_SCANCODE_BRIGHTNESSDOWN = 275,
  SDL_SCANCODE_BRIGHTNESSUP = 276,
  SDL_SCANCODE_DISPLAYSWITCH = 277,
  SDL_SCANCODE_KBDILLUMTOGGLE = 278,
  SDL_SCANCODE_KBDILLUMDOWN = 279,
  SDL_SCANCODE_KBDILLUMUP = 280,
  SDL_SCANCODE_EJECT = 281,
  SDL_SCANCODE_SLEEP = 282,
  SDL_SCANCODE_APP1 = 283,
  SDL_SCANCODE_APP2 = 284,
  SDL_SCANCODE_AUDIOREWIND = 285,
  SDL_SCANCODE_AUDIOFASTFORWARD = 286,
  SDL_NUM_SCANCODES = 512
} SDL_Scancode;
typedef Sint32 SDL_Keycode;

typedef struct SDL_Keysym {
  SDL_Scancode scancode;
  SDL_Keycode sym;
  Uint16 mod;
  Uint32 unused;
} SDL_Keysym;

typedef struct SDL_KeyboardEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 windowID;
  Uint8 state;
  Uint8 repeat;
  Uint8 padding2;
  Uint8 padding3;
  SDL_Keysym keysym;
} SDL_KeyboardEvent;

typedef struct SDL_TextEditingEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 windowID;
  char text[32];
  Sint32 start;
  Sint32 length;
} SDL_TextEditingEvent;

typedef struct SDL_TextInputEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 windowID;
  char text[32];
} SDL_TextInputEvent;

typedef struct SDL_MouseMotionEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 windowID;
  Uint32 which;
  Uint32 state;
  Sint32 x;
  Sint32 y;
  Sint32 xrel;
  Sint32 yrel;
} SDL_MouseMotionEvent;

typedef struct SDL_JoyAxisEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_JoystickID which;
  Uint8 axis;
  Uint8 padding1;
  Uint8 padding2;
  Uint8 padding3;
  Sint16 value;
  Uint16 padding4;
} SDL_JoyAxisEvent;

typedef struct SDL_MouseButtonEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 windowID;
  Uint32 which;
  Uint8 button;
  Uint8 state;
  Uint8 clicks;
  Uint8 padding1;
  Sint32 x;
  Sint32 y;
} SDL_MouseButtonEvent;

typedef struct SDL_MouseWheelEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 windowID;
  Uint32 which;
  Sint32 x;
  Sint32 y;
  Uint32 direction;
} SDL_MouseWheelEvent;

typedef struct SDL_JoyBallEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_JoystickID which;
  Uint8 ball;
  Uint8 padding1;
  Uint8 padding2;
  Uint8 padding3;
  Sint16 xrel;
  Sint16 yrel;
} SDL_JoyBallEvent;

typedef struct SDL_JoyHatEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_JoystickID which;
  Uint8 hat;
  Uint8 value;
  Uint8 padding1;
  Uint8 padding2;
} SDL_JoyHatEvent;

typedef struct SDL_JoyButtonEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_JoystickID which;
  Uint8 button;
  Uint8 state;
  Uint8 padding1;
  Uint8 padding2;
} SDL_JoyButtonEvent;

typedef struct SDL_JoyDeviceEvent {
  Uint32 type;
  Uint32 timestamp;
  Sint32 which;
} SDL_JoyDeviceEvent;

typedef struct SDL_ControllerAxisEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_JoystickID which;
  Uint8 axis;
  Uint8 padding1;
  Uint8 padding2;
  Uint8 padding3;
  Sint16 value;
  Uint16 padding4;
} SDL_ControllerAxisEvent;

typedef struct SDL_ControllerButtonEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_JoystickID which;
  Uint8 button;
  Uint8 state;
  Uint8 padding1;
  Uint8 padding2;
} SDL_ControllerButtonEvent;

typedef struct SDL_ControllerDeviceEvent {
  Uint32 type;
  Uint32 timestamp;
  Sint32 which;
} SDL_ControllerDeviceEvent;

typedef struct SDL_AudioDeviceEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 which;
  Uint8 iscapture;
  Uint8 padding1;
  Uint8 padding2;
  Uint8 padding3;
} SDL_AudioDeviceEvent;

typedef struct SDL_TouchFingerEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_TouchID touchId;
  SDL_FingerID fingerId;
  float x;
  float y;
  float dx;
  float dy;
  float pressure;
  Uint32 windowID;
} SDL_TouchFingerEvent;

typedef struct SDL_MultiGestureEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_TouchID touchId;
  float dTheta;
  float dDist;
  float x;
  float y;
  Uint16 numFingers;
  Uint16 padding;
} SDL_MultiGestureEvent;

typedef struct SDL_DollarGestureEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_TouchID touchId;
  SDL_GestureID gestureId;
  Uint32 numFingers;
  float error;
  float x;
  float y;
} SDL_DollarGestureEvent;

typedef struct SDL_DropEvent {
  Uint32 type;
  Uint32 timestamp;
  char* file;
  Uint32 windowID;
} SDL_DropEvent;

typedef struct SDL_SensorEvent {
  Uint32 type;
  Uint32 timestamp;
  Sint32 which;
  float data[6];
} SDL_SensorEvent;

typedef struct SDL_QuitEvent {
  Uint32 type;
  Uint32 timestamp;
} SDL_QuitEvent;

typedef struct SDL_OSEvent {
  Uint32 type;
  Uint32 timestamp;
} SDL_OSEvent;

typedef struct SDL_UserEvent {
  Uint32 type;
  Uint32 timestamp;
  Uint32 windowID;
  Sint32 code;
  void* data1;
  void* data2;
} SDL_UserEvent;

typedef struct SDL_SysWMEvent {
  Uint32 type;
  Uint32 timestamp;
  SDL_SysWMmsg* msg;
} SDL_SysWMEvent;

typedef union SDL_Event {
  Uint32 type;
  SDL_CommonEvent common;
  SDL_DisplayEvent display;
  SDL_WindowEvent window;
  SDL_KeyboardEvent key;
  SDL_TextEditingEvent edit;
  SDL_TextInputEvent text;
  SDL_MouseMotionEvent motion;
  SDL_MouseButtonEvent button;
  SDL_MouseWheelEvent wheel;
  SDL_JoyAxisEvent jaxis;
  SDL_JoyBallEvent jball;
  SDL_JoyHatEvent jhat;
  SDL_JoyButtonEvent jbutton;
  SDL_JoyDeviceEvent jdevice;
  SDL_ControllerAxisEvent caxis;
  SDL_ControllerButtonEvent cbutton;
  SDL_ControllerDeviceEvent cdevice;
  SDL_AudioDeviceEvent adevice;
  SDL_SensorEvent sensor;
  SDL_QuitEvent quit;
  SDL_UserEvent user;
  SDL_SysWMEvent syswm;
  SDL_TouchFingerEvent tfinger;
  SDL_MultiGestureEvent mgesture;
  SDL_DollarGestureEvent dgesture;
  SDL_DropEvent drop;
  Uint8 padding[56];
} SDL_Event;


// API
extern int SDL_PollEvent(SDL_Event* event);
extern void SDL_GetVersion(SDL_version* ver);
extern void SDL_GetWindowPosition(SDL_Window* window, int* x, int* y);
extern SDL_Window* SDL_CreateWindow(const char* title, int x, int y, int w, int h, Uint32 flags);
extern void SDL_ShowWindow(SDL_Window * window);
extern void SDL_HideWindow(SDL_Window * window);
extern void SDL_DestroyWindow(SDL_Window* window);
extern Uint32 SDL_GetWindowID(SDL_Window* window);
extern int SDL_GetWindowWMInfo(SDL_Window * window, SDL_SysWMinfo * info);
