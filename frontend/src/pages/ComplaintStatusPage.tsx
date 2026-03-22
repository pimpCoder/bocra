import { Link, useParams } from 'react-router-dom'
import ComplaintStatusResultSection from '../components/complaints/ComplaintStatusResultSection'
import { getComplaintByTrackingId } from '../utils/complaintsStorage'

function ComplaintStatusPage() {
  const { trackingId } = useParams()

  if (!trackingId) {
    return (
      <div className="py-8 md:py-10">
        <section className="rounded-2xl border border-red-200 bg-red-50 p-6">
          <h1 className="text-xl font-bold text-red-900">Tracking ID Missing</h1>
          <p className="mt-2 text-sm text-red-800">
            A tracking ID is required to view complaint status.
          </p>
          <Link className="mt-4 inline-block font-semibold text-blue-700 hover:text-blue-800" to="/complaints/track">
            Back to Track Complaint
          </Link>
        </section>
      </div>
    )
  }

  const decodedTrackingId = decodeURIComponent(trackingId)
  const record = getComplaintByTrackingId(decodedTrackingId)

  if (!record) {
    return (
      <div className="py-8 md:py-10">
        <section className="rounded-2xl border border-red-200 bg-red-50 p-6">
          <h1 className="text-xl font-bold text-red-900">Complaint Not Found</h1>
          <p className="mt-2 text-sm text-red-800">
            No complaint was found for tracking ID <span className="font-mono">{decodedTrackingId}</span>.
          </p>
          <Link className="mt-4 inline-block font-semibold text-blue-700 hover:text-blue-800" to="/complaints/track">
            Try another tracking ID
          </Link>
        </section>
      </div>
    )
  }

  return (
    <div className="py-8 md:py-10">
      <div className="mb-6 rounded-2xl border border-slate-200 bg-slate-50 p-5 md:p-6">
        <p className="text-sm text-slate-700 md:text-base">
          Want to check another complaint?{' '}
          <Link className="font-semibold text-blue-700 hover:text-blue-800" to="/complaints/track">
            Track Complaint
          </Link>
          .
        </p>
      </div>
      <ComplaintStatusResultSection record={record} />
    </div>
  )
}

export default ComplaintStatusPage
