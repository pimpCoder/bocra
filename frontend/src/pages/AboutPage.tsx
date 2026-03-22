import { useEffect, useMemo, useRef, useState } from 'react'
import { FiArrowLeft, FiArrowRight } from 'react-icons/fi'
import { aboutSections } from '../data/about'

function AboutPage() {
  const sections = useMemo(() => aboutSections, [])

  const containerRef = useRef<HTMLDivElement | null>(null)
  const touchStartRef = useRef<{ x: number; y: number } | null>(null)
  const [isMobile, setIsMobile] = useState<boolean>(false)
  const [activeIndex, setActiveIndex] = useState<number>(0)
  const totalSections = sections.length

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 768)
    handleResize()
    window.addEventListener('resize', handleResize)
    return () => window.removeEventListener('resize', handleResize)
  }, [])

  const scrollToSection = (index: number) => {
    const container = containerRef.current
    if (!container) return

    const nextIndex = Math.min(Math.max(index, 0), totalSections - 1)
    const offset = isMobile
      ? nextIndex * container.clientHeight
      : nextIndex * container.clientWidth

    container.scrollTo({
      top: isMobile ? offset : 0,
      left: isMobile ? 0 : offset,
      behavior: 'smooth',
    })
  }

  useEffect(() => {
    const container = containerRef.current
    if (!container) return

    const handleScroll = () => {
      const size = isMobile ? container.clientHeight : container.clientWidth
      const position = isMobile ? container.scrollTop : container.scrollLeft
      const index = Math.round(position / Math.max(size, 1))
      setActiveIndex(Math.min(Math.max(index, 0), totalSections - 1))
    }

    const handleWheel = (event: WheelEvent) => {
      if (isMobile) return
      event.preventDefault()
      container.scrollBy({
        left: event.deltaY + event.deltaX,
        behavior: 'smooth',
      })
    }

    const handleTouchStart = (event: TouchEvent) => {
      const touch = event.touches[0]
      touchStartRef.current = { x: touch.clientX, y: touch.clientY }
    }

    const handleTouchEnd = (event: TouchEvent) => {
      if (!touchStartRef.current) return

      const touch = event.changedTouches[0]
      const deltaX = touchStartRef.current.x - touch.clientX
      const deltaY = touchStartRef.current.y - touch.clientY
      const threshold = 40

      if (isMobile) {
        if (Math.abs(deltaY) > threshold && Math.abs(deltaY) > Math.abs(deltaX)) {
          scrollToSection(activeIndex + (deltaY > 0 ? 1 : -1))
        }
      } else if (Math.abs(deltaX) > threshold && Math.abs(deltaX) > Math.abs(deltaY)) {
        scrollToSection(activeIndex + (deltaX > 0 ? 1 : -1))
      }

      touchStartRef.current = null
    }

    handleScroll()
    container.addEventListener('scroll', handleScroll, { passive: true })
    container.addEventListener('wheel', handleWheel, { passive: false })
    container.addEventListener('touchstart', handleTouchStart, { passive: true })
    container.addEventListener('touchend', handleTouchEnd, { passive: true })

    return () => {
      container.removeEventListener('scroll', handleScroll)
      container.removeEventListener('wheel', handleWheel)
      container.removeEventListener('touchstart', handleTouchStart)
      container.removeEventListener('touchend', handleTouchEnd)
    }
  }, [activeIndex, isMobile, totalSections])

  return (
    <div className="relative">
      {/* Main scroll container: horizontal snap on desktop/tablet, vertical snap on mobile */}
      <div
        ref={containerRef}
        className={`w-screen ml-[calc(50%-50vw)] mr-[calc(50%-50vw)] ${
          isMobile
            ? 'h-[100svh] overflow-y-auto overflow-x-hidden snap-y snap-mandatory'
            : 'h-[100svh] overflow-x-auto overflow-y-hidden snap-x snap-mandatory flex'
        } scroll-smooth [scrollbar-width:none] [&::-webkit-scrollbar]:hidden`}
      >
        {sections.map((section, index) => {
          const bgShade = index % 2 === 0 ? 'bg-slate-100' : 'bg-slate-200/70'

          return (
            /* Individual full-screen sections with alternating neutral shades */
            <section
              key={section.id}
              className={`snap-start ${
                isMobile
                  ? `h-[100svh] w-full ${bgShade}`
                  : `h-[100svh] w-screen shrink-0 ${bgShade}`
              }`}
            >
              <div
                className={`mx-auto flex h-full w-full max-w-6xl items-center px-6 py-16 md:px-10 ${
                  activeIndex === index ? 'opacity-100 translate-y-0' : 'opacity-95 translate-y-2'
                } transition-all duration-500`}
              >
                <div className="w-full">
                  <h1 className="text-balance text-3xl font-extrabold tracking-tight text-slate-900 md:text-5xl">
                    {section.heading}
                  </h1>
                  <p className="mt-4 max-w-4xl text-pretty text-base leading-relaxed text-slate-700 md:text-lg">
                    {section.summary}
                  </p>

                  {section.bullets.length > 0 ? (
                    <ul className="mt-6 grid gap-3 md:grid-cols-2">
                      {section.bullets.map((point) => (
                        <li
                          key={point}
                          className="list-disc text-sm leading-relaxed text-slate-700 md:ml-6 md:text-base"
                        >
                          {point}
                        </li>
                      ))}
                    </ul>
                  ) : null}
                </div>
              </div>
            </section>
          )
        })}
      </div>

      {/* Arrow navigation for desktop/tablet horizontal flow */}
      {!isMobile ? (
        <div className="pointer-events-none fixed inset-x-0 top-1/2 z-30 mx-auto flex w-full max-w-[98vw] -translate-y-1/2 justify-between px-3">
          <button
            type="button"
            onClick={() => scrollToSection(activeIndex - 1)}
            className="pointer-events-auto inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-300 bg-white/95 text-slate-700 shadow-sm transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-45"
            disabled={activeIndex === 0}
            aria-label="Go to previous section"
          >
            <FiArrowLeft />
          </button>
          <button
            type="button"
            onClick={() => scrollToSection(activeIndex + 1)}
            className="pointer-events-auto inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-300 bg-white/95 text-slate-700 shadow-sm transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-45"
            disabled={activeIndex === totalSections - 1}
            aria-label="Go to next section"
          >
            <FiArrowRight />
          </button>
        </div>
      ) : null}

      {/* Fixed bottom progress indicator: clickable dots + current index */}
      <div className="fixed bottom-5 left-1/2 z-40 -translate-x-1/2 rounded-full border border-slate-300/80 bg-white/90 px-4 py-2 shadow-lg backdrop-blur">
        <div className="flex items-center gap-3">
          <div className="flex items-center gap-2">
            {sections.map((section, index) => (
              <button
                key={section.id}
                type="button"
                onClick={() => scrollToSection(index)}
                className={`h-2.5 w-2.5 rounded-full transition ${
                  activeIndex === index ? 'bg-slate-900 scale-110' : 'bg-slate-400 hover:bg-slate-500'
                }`}
                aria-label={`Go to section ${index + 1}`}
              />
            ))}
          </div>
          <p className="text-xs font-semibold tracking-wide text-slate-700">
            {activeIndex + 1}/{totalSections}
          </p>
        </div>
      </div>
    </div>
  )
}

export default AboutPage
