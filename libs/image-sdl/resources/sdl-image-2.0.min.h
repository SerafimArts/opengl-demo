extern const SDL_version * IMG_Linked_Version(void);

typedef enum
{
    IMG_INIT_JPG    = 0x00000001,
    IMG_INIT_PNG    = 0x00000002,
    IMG_INIT_TIF    = 0x00000004,
    IMG_INIT_WEBP   = 0x00000008,
    IMG_INIT_JXL    = 0x00000010,
    IMG_INIT_AVIF   = 0x00000020
} IMG_InitFlags;

extern int IMG_Init(int flags);
extern void IMG_Quit(void);

extern SDL_Surface * IMG_LoadTyped_RW(SDL_RWops *src, int freesrc, const char *type);
extern SDL_Surface * IMG_Load(const char *file);
extern SDL_Surface * IMG_Load_RW(SDL_RWops *src, int freesrc);

extern SDL_Texture * IMG_LoadTexture(SDL_Renderer *renderer, const char *file);
extern SDL_Texture * IMG_LoadTexture_RW(SDL_Renderer *renderer, SDL_RWops *src, int freesrc);
extern SDL_Texture * IMG_LoadTextureTyped_RW(SDL_Renderer *renderer, SDL_RWops *src, int freesrc, const char *type);

extern int IMG_isAVIF(SDL_RWops *src);
extern int IMG_isICO(SDL_RWops *src);
extern int IMG_isCUR(SDL_RWops *src);
extern int IMG_isBMP(SDL_RWops *src);
extern int IMG_isGIF(SDL_RWops *src);
extern int IMG_isJPG(SDL_RWops *src);
extern int IMG_isJXL(SDL_RWops *src);
extern int IMG_isLBM(SDL_RWops *src);
extern int IMG_isPCX(SDL_RWops *src);
extern int IMG_isPNG(SDL_RWops *src);
extern int IMG_isPNM(SDL_RWops *src);
extern int IMG_isSVG(SDL_RWops *src);
extern int IMG_isQOI(SDL_RWops *src);
extern int IMG_isTIF(SDL_RWops *src);
extern int IMG_isXCF(SDL_RWops *src);
extern int IMG_isXPM(SDL_RWops *src);
extern int IMG_isXV(SDL_RWops *src);
extern int IMG_isWEBP(SDL_RWops *src);

extern SDL_Surface * IMG_LoadAVIF_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadICO_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadCUR_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadBMP_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadGIF_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadJPG_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadJXL_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadLBM_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadPCX_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadPNG_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadPNM_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadSVG_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadQOI_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadTGA_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadTIF_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadXCF_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadXPM_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadXV_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadWEBP_RW(SDL_RWops *src);
extern SDL_Surface * IMG_LoadSizedSVG_RW(SDL_RWops *src, int width, int height);

extern SDL_Surface * IMG_ReadXPMFromArray(char **xpm);
extern SDL_Surface * IMG_ReadXPMFromArrayToRGB888(char **xpm);

extern int IMG_SavePNG(SDL_Surface *surface, const char *file);
extern int IMG_SavePNG_RW(SDL_Surface *surface, SDL_RWops *dst, int freedst);
extern int IMG_SaveJPG(SDL_Surface *surface, const char *file, int quality);
extern int IMG_SaveJPG_RW(SDL_Surface *surface, SDL_RWops *dst, int freedst, int quality);

typedef struct
{
    int w, h;
    int count;
    SDL_Surface **frames;
    int *delays;
} IMG_Animation;

extern IMG_Animation * IMG_LoadAnimation(const char *file);
extern IMG_Animation * IMG_LoadAnimation_RW(SDL_RWops *src, int freesrc);
extern IMG_Animation * IMG_LoadAnimationTyped_RW(SDL_RWops *src, int freesrc, const char *type);
extern void IMG_FreeAnimation(IMG_Animation *anim);
extern IMG_Animation * IMG_LoadGIFAnimation_RW(SDL_RWops *src);
